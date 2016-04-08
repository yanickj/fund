<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class FilterType
 *
 * @package AppBundle\Form
 * @author Józef Janik <joe@getsidecar.com>
 */
class FilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('filter', 'choice', [
            'choices' => [
                'expire' => 'Expiring Soon',
                'me' => 'Mine',
            ],
            'label' => false
        ]);
        $builder->add('search', 'submit');
    }

}