<?php
namespace Yannickl88\FeaturesBundle;

use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Yannickl88\FeaturesBundle\DependencyInjection\Compiler\AddFeaturesCompilerPass;
use Yannickl88\FeaturesBundle\DependencyInjection\Compiler\FeaturesCompilerPass;

/**
 * @author Yannick de Lange <yannick.l.88@gmail.com>
 */
final class FeaturesBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new AddFeaturesCompilerPass());
        $container->addCompilerPass(new FeaturesCompilerPass(), PassConfig::TYPE_BEFORE_REMOVING);
    }
}
