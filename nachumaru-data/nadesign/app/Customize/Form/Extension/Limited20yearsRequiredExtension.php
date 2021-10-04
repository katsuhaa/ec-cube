<?php

namespace Customize\Form\Extension;

use Eccube\Form\Type\Front\EntryType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;


class Limited20yearsRequiredExtension extends AbstractTypeExtension
{
    
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $options = $builder->get('birth')->getOptions();

        $options['required'] = true;
        $options['constraints'] = [
            new Assert\LessThanOrEqual([
                'value' => date('Y-m-d', strtotime('-20 year')),
                'message' => 'form_error.Under_20years_old',
            ])
        ];

        $builder->add('birth', BirthdayType::class, $options);
        log_debug($options['required']);
        log_debug('２０歳以上必須');
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return EntryType::class;
    }
}

