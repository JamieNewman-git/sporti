<?php


namespace App\Model\ORM\Repository;



use _HumbugBox5f65e914a905\Nette\Utils\DateTime;
use App\Model\ORM\Entity\Brand;
use App\Model\ORM\Decorator;

class BrandRepository
{
    /**
 * @var Decorator
 */
    private $entityManager;

    public function __construct(Decorator $decorator) {
        $this->entityManager = $decorator;
    }

    /**
     * @return Decorator
     */
    public function getEntityManager() {
        return $this->entityManager;
    }

    /**
     * @param int $id
     */
    public function softDelete(int $id) {
        /** @var Brand $brand */
        $brand = $this->entityManager->getRepository(Brand::class)->find($id);
        $brand->setDeletedAt(new DateTime());

        $this->entityManager->persist($brand);
        $this->entityManager->flush();
    }

    /**
     * @param int $bid
     * @return object|null
     */
    public function getBrand(int $bid) : ?Brand {
        return $this->entityManager->getRepository(Brand::class)->find($bid);
    }

    /**
     * @param string $name
     * @return object|null
     */
    public function getBrandByName(string $name) : ?Brand {
        return $this->entityManager->getRepository(Brand::class)->findOneBy(['name' => $name]);
    }

    /**
     * @param int $first
     * @param int $limit
     * @param string $sort
     * @return array
     */
    public function getData(int $first, int $limit, string $sort) : array {
    return $this->entityManager->getRepository(Brand::class)->findBy(["deletedAt"=>NULL],["name"=>$sort],$limit,$first);
    }

    /**
     * @return int
     */
    public function getLength() : int {
        return count($this->entityManager->getRepository(Brand::class)->findBy(["deletedAt"=>NULL]));/*findBy(["deletedAt"=>NULL])*/
    }
}