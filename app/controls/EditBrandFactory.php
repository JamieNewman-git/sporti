<?php


namespace App\Controls;


use App\Model\ORM\Entity\Brand;
use App\Model\ORM\Repository\BrandRepository;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;

class EditBrandFactory extends Control
{
    /** @var Brand */
    private Brand $brand;

    /** @var BrandRepository */
    private BrandRepository $brandRepository;

    /**
     * EditBrandFactory constructor.
     * @param Brand $brand
     * @param BrandRepository $brandRepository
     */
    public function __construct(Brand $brand, BrandRepository $brandRepository)
    {
        $this->brand = $brand;
        $this->brandRepository = $brandRepository;
    }

    /**
     * @return Form
     */
    protected function createComponentForm(): Form
    {
        $form = new Form();

        $form->addText('name', 'Brand Name:')->setRequired('Please fill brand name');
        $form->addHidden("bid");
        $form->addHidden("limit");
        $form->addHidden("first");
        $form->addHidden("sort");

        $form->addSubmit('save', 'Save');

        $form->onSuccess[] = [$this, 'processFormSucceeded'];
        return $form;
    }

    /**
     * @param Form $form
     * @throws \Nette\Application\AbortException
     */
    public function processFormSucceeded(Form $form): void
    {
        $values = $form->getValues();
        $limit = $values->limit;
        $first = $values->first;
        $sort = $values->sort;

        if($this->brandRepository->getBrandByName($values->name) !== NULL) {
            $this->getPresenter()->flashMessage('Brand with this name already existed.', 'error');
            $this->getPresenter()->redirect('Homepage:default',["limit"=>$limit,"first"=>$first,"sort"=>$sort]);
        }
        if ($values->bid == NULL)
            $brand = new Brand($values->name);
        else {
            $brand = $this->brandRepository->getBrand($values->bid);
            $brand->setName($values->name);
        }
        $this->brandRepository->getEntityManager()->persist($brand);
        $this->brandRepository->getEntityManager()->flush();

        $this->getPresenter()->flashMessage('You have successfully saved brand.', 'success');
        $this->getPresenter()->redirect('Homepage:default',["limit"=>$limit,"first"=>$first,"sort"=>$sort]);
    }

    public function render(): void
    {
        $this->template->header = "New Brand";
        $this->template->render(__DIR__ . '/templates/editBrand.latte');
    }

}


interface IEditBrandFactory
{
    /**
     * @return EditBrandFactory
     */
    function create(): EditBrandFactory;
}