<?php

namespace App\Factory;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<User>
 * 
 */
final class UserFactory extends PersistentProxyObjectFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct(
        private UserPasswordHasherInterface $userPasswordHasher
        )
    {
        parent::__construct();
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    #[\Override]
    protected function defaults(): array|callable
    {
        return [
            'email' => self::faker()->unique()->safeEmail(),
            'firstname' => self::faker()->unique()->firstName(),
            'lastname' => self::faker()->unique()->lastName(),
            'roles' => [self::faker()->randomElement(['ROLE_ADMIN', 'ROLE_AJOUT_DE_LIVRE', 'ROLE_EDITION_DE_LIVRE'])],
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    #[\Override]
    protected function initialize(): static
    {
        return $this
            ->afterInstantiate(function(User $user): void {
                $user->setPassword(
                    $this->userPasswordHasher->hashPassword($user, 'biblios1234')
                );
            })
        ;
    }

    #[\Override]
    public static function class(): string
    {
        return User::class;
    }
}
