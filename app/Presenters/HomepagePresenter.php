<?php

declare(strict_types=1);

namespace App\Presenters;


use App\Controls\GridFactory;
use App\Controls\IEditBrandFactory;
use App\Model\ORM\Repository\BrandRepository;

final class HomepagePresenter extends BasePresenter
{

    /** @var IEditBrandFactory @inject */
    public $_editBrandFactory;

    /**
     * @var BrandRepository @inject */
    public $brandRepository;

    /** @var GridFactory @inject */
    public $gridFactory;

	public function renderDefault(): void
	{
        $limit = $this->template->limit = (int)$this->getParameter('limit');
	    $first = $this->template->first = (int)$this->getParameter('first');
        $sort = $this->template->sort = $this->getParameter('sort');

        $form = $this['brandForm']->getComponent("form");
        $form->setValues([
            "limit" => $limit,
            "first" => $first,
            "sort" => $sort
        ]);

	    $this->template->grid = $this->grid($limit,$first,$sort);
	}

    /**
     * @return \App\Controls\EditBrandFactory
     */
    protected function createComponentBrandForm()
    {
        return $this->_editBrandFactory->create();
    }

    /**
     * @param int $limit
     * @param int $first
     * @param $sort
     * @return string
     * @throws \Nette\Application\UI\InvalidLinkException
     */
    public function grid(int $limit = NULL, int $first = NULL, string $sort = NULL) {
        return $this->gridFactory->renderData($limit, $first,$sort);
    }

    /**
     * @param $id
     * @param $limit
     * @param $first
     * @param $sort
     * @throws \Nette\Application\AbortException
     */
    public function actionDelete(int $id, int $limit = NULL, int $first = NULL, string $sort = NULL) : void {
	    $this->brandRepository->softDelete((int)$id);
	    $this->redirect("Homepage:default",["limit"=>(int)$limit,"first"=>(int)$first,"sort"=>$sort]);
    }

    /**
     * @param int $id
     * @param int|null $limit
     * @param int|null $first
     * @param string|null $sort
     */
    public function handleRename(int $id, int $limit = NULL, int $first = NULL, string $sort = NULL) : void {

        $brand = $this->brandRepository->getBrand($id);

        $form = $this['brandForm']->getComponent("form");
        $form->setValues([
            "bid" => $id,
            "name" => $brand->getName(),
            "limit" => $limit,
            "first" => $first,
            "sort" => $sort
        ]);

        $this->template->redrawed = TRUE;
        $this->template->caption = "Edit Brand";

        $this->redrawControl('brandForm');
        $this->redrawControl('scripts');
    }

    /**
     * @param int|null $limit
     * @param int|null $first
     * @param string|null $sort
     */
    public function handleNew(int $limit = NULL, int $first = NULL, string $sort = NULL) : void {

        $form = $this['brandForm']->getComponent("form");
        $form->setValues([
            "bid" => NULL,
            "name" => NULL,
            "limit" => $limit,
            "first" => $first,
            "sort" => $sort
        ]);

        $this->template->redrawed = TRUE;
        $this->template->caption = "New Brand";

        $this->redrawControl('brandForm');
        $this->redrawControl('scripts');
    }
}
