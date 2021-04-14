<?php
namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\Lesson;
use Doctrine\ORM\EntityManagerInterface;

final class LessonProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
{
    private $en;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return Lesson::class === $resourceClass;
    }

    public function getItem(string $resourceClass, $id, ?string $operationName = null, array $context = [])
    {
        return $this->em->getRepository(Lesson::class)->findOneById($id);
    }
}
