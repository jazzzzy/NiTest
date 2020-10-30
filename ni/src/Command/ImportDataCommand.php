<?php declare(strict_types = 1);

namespace App\Command;

use App\Entity\Product;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Console command to import messages from json file
 */
final class ImportDataCommand extends Command
{
    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    const DATA_FILE_PATH = './data/';
    const DATA_MAPPER = [
        ['entity' => User::class, 'filename' => 'users.csv'],
        ['entity' => Product::class, 'filename' => 'products.csv'],
    ];

    const RELATION_USER_FIELD = 'user_id';
    const RELATION_SKU_FIELD = 'product_sku';

    /**
     * ImportMessagesCommand constructor.
     *
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        SerializerInterface $serializer,
        EntityManagerInterface $entityManager
    ) {
        parent::__construct();
        $this->serializer = $serializer;
        $this->entityManager = $entityManager;
    }
    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setName('import:data')
            ->setDescription('Imports fixture data files')
        ;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $userRepo = $this->entityManager->getRepository(User::class);
        $firstUser = $userRepo->find(1);

        if ($firstUser) {
            throw new \RuntimeException('This command should only be called once to initialize the database');
        }

        foreach (self::DATA_MAPPER as $mapper) {
            try {
                $content = file(self::DATA_FILE_PATH . $mapper['filename']);
            } catch(\Exception $e) {
                $output->writeln(sprintf('File %s is not readable or doesn\'t exist', $mapper['filename']));
            }

            $property = [];
            foreach ($content as $rowNum => $row) {
                $csvRow = str_getcsv($row);

                if ($rowNum === 0) {
                    foreach ($csvRow as $key => $name) {
                        $property[$key] = $name;
                    }

                    continue;
                }

                $entry = [];
                foreach ($csvRow as $key => $value) {
                    $entry[$property[$key]] = $value;
                }

                $data = $this->serializer->denormalize($entry, $mapper['entity'], 'array');

                $this->entityManager->persist($data);
                $this->entityManager->flush();
                $this->entityManager->clear();
            }
        }

        try {
            $content = file(self::DATA_FILE_PATH . 'purchased.csv');
        } catch(\Exception $e) {
            $output->writeln(sprintf('File %s is not readable or doesn\'t exist', 'purchased.csv'));
        }

        $property = [];
        foreach ($content as $rowNum => $row) {
            $csvRow = str_getcsv($row);

            if ($rowNum === 0) {
                foreach ($csvRow as $key => $name) {
                    $property[$key] = $name;
                }

                continue;
            }

            $entry = [];
            foreach ($csvRow as $key => $value) {
                $entry[$property[$key]] = $value;
            }

            /** @var User $user */
            $user = $this->entityManager->getRepository(User::class)->find($entry[self::RELATION_USER_FIELD]);
            /** @var Product $product */
            $product = $this->entityManager->getRepository(Product::class)->findOneBy(['sku' => $entry[self::RELATION_SKU_FIELD]]);
            $user->addProduct($product);

            $this->entityManager->flush();
        }

        return 1;
    }
}