<?php

namespace Customize\Form\Extension;

use Eccube\Common\EccubeConfig;
use Eccube\Form\Type\Front\NonMemberType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;


class Limited20yearsNonMemberRequiredExtension extends AbstractTypeExtension
{
    public function __construct(EccubeConfig $eccubeConfig)
    {
        $this->eccubeConfig = $eccubeConfig;
    }
    
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('birth', BirthdayType::class, [
                'required' => true,
                'input' => 'datetime',
                'years' => range(date('Y'), date('Y') - $this->eccubeConfig['eccube_birth_max']),
                'widget' => 'choice',
                'format' => 'yyyy/MM/dd',
                'placeholder' => ['year' => '----', 'month' => '--', 'day' => '--'],
                'constraints' => [
                    new Assert\LessThanOrEqual([
                        'value' => date('Y-m-d', strtotime('-20 year')),
                        'message' => 'form_error.Under_20years_old_nonmember',
                    ]),
                ],
        ]);
        log_debug($options['required']);
        log_debug('２０歳以上必須');
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return NonMemberType::class;
    }
}

