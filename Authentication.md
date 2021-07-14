****
__HOW TO IMPLEMENT THE AUTHENTICATION__
****

__User Entity__

```injectablephp
/**
 * @ORM\Table("user")
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"username"}, message="There is already an account with this username")
 */
class User implements UserInterface
{
    //...
}
```

****
__Setup security.yaml__

*Provider*

```yaml
security:
    providers:
        app_user_provider:
          entity:
              class: App\Entity\User
              property: username
```
Indicates to Symfony where can be found the user, in User entity and the defines which attribute is used for authentication.


*Password encryption*
```yaml
security:
    encoders:
        App\Entity\User:
            algorithm: auto

```
Bcrypt encoder is used to encrypt the passwords before recording in Database


*Firewall*
```yaml
security:
    firewalls:
        dev:
            pattern: ^/(_(profiler|wdt)|css|images|js)/
            security: false
        main:
            anonymous: true
            lazy: true
            provider: app_user_provider
            guard:
                authenticators:
                    - App\Security\UserAuthenticator
            logout:
                path: user.logout
                target: /
```
The firewall defines the authentication's process.


*Access control*
```yaml
security:
    access_control:
      - { path: ^/user/edit, roles: ROLE_ADMIN }
      - { path: ^/user/list, roles: ROLE_ADMIN }
      - { path: ^/task/create, roles: ROLE_USER }
      - { path: ^/task/edit, roles: ROLE_USER }
```
The access control is set here :

* the path /users only to logged user with the ROLE_ADMIN
* the path /task to all logged users
****
__STORAGE__

After the user has been connected on the website, it will be sorted in a cookie.