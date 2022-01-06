<?php

namespace App\Admin;

use App\Entity\Pet;
use JetBrains\PhpStorm\Pure;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class PetAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $form): void
    {
        $form->add('name', TextType::class);
        $form->add('owner');
    }

    protected function configureDatagridFilters(DatagridMapper $datagrid): void
    {
        $datagrid->add('name');
        $datagrid->add('owner');
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list->addIdentifier('name');
        $list->add('owner');
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show->add('name');
        $show->add('owner');
    }

    #[Pure] public function toString(object $object): string
    {
        return $object instanceof Pet
            ? $object->__toString()
            : 'Mascota'; // shown in the breadcrumb on the create view
    }
}