<?php

namespace App\Tests;

use App\Form\SearchFormType;
use App\Model\SearchData;
use Symfony\Component\Form\Test\TypeTestCase;


class SearchFormTypeTest extends TypeTestCase
{
    /**
     * La méthode testFiltersHasCorrectData() vérifie qu'une fois que
     * des filtres ont été sélectionnés, les salles affichées sont les bonnes.
     * @return void
     */
    public function testFiltersHasCorrectData()
    {
        $formData = [
            'q' => 'D1',
            'floors' => [0, 1],
            'acquisitionUnitState' => ['En attente d\'affectation', 'Opérationnel'],
        ];

        $form = $this->factory->create(SearchFormType::class);

        $searchData = new SearchData();
        $searchData->q = $formData['q'];
        $searchData->floors = $formData['floors'];
        $searchData->acquisitionUnitState = $formData['acquisitionUnitState'];

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($searchData, $form->getData());
    }
}