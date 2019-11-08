<?php
namespace Yannickl88\FeaturesBundle\Feature;

/**
 * Container for multiple FeatureResolverInterface instances, when resolve is
 * called the final value is calculated.
 *
 * @author Yannick de Lange <yannick.l.88@gmail.com>
 */
class Resolver
{
    const STRATEGY_AFFIRMATIVE = 'affirmative';
    const STRATEGY_UNANIMOUS   = 'unanimous';

    /**
     * @var FeatureResolverInterface[]
     */
    private $resolvers = [];

    /**
     * @var array
     */
    private $resolver_options = [];

    public function addResolver(FeatureResolverInterface $resolver, array $options = []): void
    {
        $key = spl_object_hash($resolver);

        $this->resolvers[$key]        = $resolver;
        $this->resolver_options[$key] = $options;
    }

    /**
     * Resolve the final value.
     *
     * @throws \InvalidArgumentException when strategy is unknown.
     */
    public function resolve(string $strategy = self::STRATEGY_UNANIMOUS): bool
    {
        switch ($strategy) {
            case self::STRATEGY_AFFIRMATIVE:
                return $this->resolveAffirmative();
            case self::STRATEGY_UNANIMOUS:
                return $this->resolveUnanimous();
        }

        throw new \InvalidArgumentException(sprintf('The strategy "%s" is not supported.', $strategy));
    }

    /**
     * Resolve the feature where at least one voter needs to resolve true for
     * the feature to be active.
     */
    private function resolveAffirmative(): bool
    {
        foreach ($this->resolvers as $key => $resolver) {
            if ($resolver->isActive($this->resolver_options[$key])) {
                return true;
            }
        }

        return false;
    }

    /**
     * Resolve the feature where all the voters needs to resolve true for
     * the feature to be active.
     */
    private function resolveUnanimous(): bool
    {
        foreach ($this->resolvers as $key => $resolver) {
            if (!$resolver->isActive($this->resolver_options[$key])) {
                return false;
            }
        }

        return true;

    }
}
