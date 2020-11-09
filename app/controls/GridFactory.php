<?php


namespace App\Controls;

use App\Model\ORM\Entity\Brand;
use App\Model\ORM\Repository\BrandRepository;
use Nette\Application\LinkGenerator;
use Nette\Utils\Html;

class GridFactory
{
    /** @var LinkGenerator */
    private $linkGenerator;

    /**
     * GridFactory constructor.
     * @param Brand $brand
     * @param BrandRepository $brandRepository
     */
    public function __construct(Brand $brand,BrandRepository $brandRepository,LinkGenerator $generator)
    {
        $this->brand = $brand;
        $this->brandRepository = $brandRepository;
        $this->linkGenerator = $generator;
    }

    /**
     * @param int|null $limit
     * @param int|null $first
     * @param string|null $sort
     * @return string
     * @throws \Nette\Application\UI\InvalidLinkException
     */
    public function renderData(int $limit = NULL, int $first = NULL, string $sort = NULL) {
        $limit = $limit == NULL ? 10 : $limit;
        $sort = $sort == NULL ? "ASC" : $sort;

        $lengthOfDB = $this->brandRepository->getLength();
        $countOfPages = (ceil($lengthOfDB / $limit));
        $bigData = $this->brandRepository->getData($first, $limit, $sort);

        $table = "<table class='grid'>
                    <thead>
                        <tr class='card-panel'>
                            <th class='row'>
                                <span class='col s1'>
                                    Brand
                                </span>
                                <span class='btn-block col'>
                                    <a href='" . $this->linkGenerator->link("Homepage:default", ["limit"=>$limit,"first"=>$first,"sort"=>"DESC"]) . "'>
                                        <span class='material-icons'>arrow_upward</span>
                                    </a>
                                </span>
                                <span class='btn-block col'>
                                    <a href='" . $this->linkGenerator->link("Homepage:default", ["limit"=>$limit,"first"=>$first,"sort"=>"ASC"]) . "'>
                                        <span class='material-icons'>arrow_downward</span>
                                    </a>
                                </span>
                            </th>
                            <th>Action</th>
                        </tr>
                    </thead>
                <tbody>";

        foreach($bigData as $b) {
            $renameButton = Html::el('a')
                ->setText("rename")
                ->class("actionButton")
                ->href("?do=rename&id=". $b->getId() ."&limit=". $limit ."&first=". $first ."&sort=". $sort);

            $deleteButton = Html::el('a')
                ->setText("delete")
                ->class("actionButton")
                ->href($this->linkGenerator->link("Homepage:delete", ["id" => $b->getId(), "limit" => $limit, "first" => $first, "sort" => $sort]));

            $table .= "<tr>
                            <td>" . $b->getName() . "</td>
                            <td>
                                " . $deleteButton . "
                                " . $renameButton . "
                            </td>
                        </tr>";
        }

        $table .= "<tr class='noBorderBottom'><div class='row'><td></td><td><ul class='pagination right'>";
        $countToShow = 5;
        $actualPage = floor($first/$limit);
        for ($i = $actualPage - 6;$i < $actualPage+$countToShow;$i++) {
            if ($i >= 0) {
                if ($i < ($countOfPages-1)) {
                    $table .= "<li class='";
                    if ($actualPage == $i) {$table .=  'active';}
                    else {$table .= 'waves-effect';}

                    $table .= "'><a href='?limit=" . $limit . "&first=" . ($i*$limit) ."' target='_self'>" . ($i+1) . "</a></li>";
                }
            }
        }
        $table .= " ... <li class='";
        if ($actualPage == ($countOfPages-1))
            $table .=  'active';
        else
            $table .= 'waves-effect';

        $table .= "'><a href='?limit=" . $limit . "&first=" . ((($countOfPages-1)*$limit)) ."'>" . ($countOfPages) . "</a></li>";
        $table .= "</td></tr></ul></div>";

        $table .= "<tr class='noBorderBottom'><div class='row'><td></td><td><span class='right'>
                        <a href='?limit=30&first=" . $first ."' target='_self'>
                            <span class='badge'>30</span>
                        </a>
                        <a href='?limit=20&first=" . $first ."' target='_self'>
                            <span class='badge'>20</span>
                        </a>
                        <a href='?limit=10&first=" . $first ."'>
                            <span class='badge'>10</span>
                        </a>
                    </span></td></tr>";
        $table .= "</tbody></table>";
        return $table;
    }
}
