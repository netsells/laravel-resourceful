# Laravel HTTP Resources

Package that helps you use Eloquent API Resources without killing your database! Say goodbye to N+1 :wave:


## The Problem

Picture a User model that has an "avatar" relation to a File model:

```php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'avatar_url' => $this->avatar->url,
        ];
    }
}
```

To return a list of users from our API, we could have such a controller action:

```php
namespace App\Http\Controllers;

use App\Models\User;

class UserController extends \Illuminate\Routing\Controller
{
    public function index()
    {
        return UserResource::collection(User::paginate());
    }
}
```

In the example above:
- if there is 1 user in our database, 2 queries will be executed
- if there are 100 users in our database, 101 queries will be executed - 1 to get the user, and then 1 query for each avatar for each user

We have a classic N+1 problem. The usual solution in Laravel is to perform eager loading within the controller:

```php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Routing\Controller;

class UserController extends Controller
{
    public function index()
    {
        return UserResource::collection(User::query()->with('avatar')->paginate());
    }
}
```

This works, but has few problems:
- requires the controller to be aware of inner structure of each API resource
  - in the example above, our controller had to know that we need access to avatar relation in the User resource
  - this is hard to maintain, as we need to keep multiple places in sync
- it's hard to make the eager loading conditional
  - in the example above, we may want to return the avatar_url only for users on a certain membership plan
  - and if we won't be returning the avatar_url, then why load the Avatar model in the first place?
- it's hard to keep the eager loading DRY, since it is tied to the controller and not the API resource
  - imagine an ArticleResource, that returns a list of related reviewers, each reviewer being a UserResource
  - fetching the Articles in controller would need to eager load not just the related users, but also the avatars for each user
- since the eager loading is spread in multiple places, any N+1 optimisation is done for each place separately and benefits only that place
  - other endpoints relying on affected API resource will still have N+1 problem

This package fixes all those problems and makes the N+1 problem within your API a thing of the past!


## Installation

using composer:

```bash
composer require netsells/laravel-http-resources
```


## Usage

This package is a drop in replacement for the official API Resources. Instead of extending the builtin `Illuminate\Http\Resources\Json\JsonResource` we extend `Netsells\Http\Resources\Json\JsonResource`.

Continuing with the example above, we get:

```php
namespace App\Http\Resources;

use Netsells\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'avatar_url' => $this->avatar->url,
        ];
    }
}
```

Of course, this doesn't change anything and things still work as they did with N+1 problem, however this first step allows you to easily switch over to this package.

There are few ways now to supercharge your API resource.

### Preloads method

First way is to select the relations for eager loading in a dedicated `preloads` method.
You should return one or more "deferred resources", which can be easily created by calling `preload` helper with a list of relations you want loaded.
The `preloads` method can optionally accept a Laravel Request as its first argument. Within the `preloads` method we have access to the Eloquent model being resolved.
This allows us to introduce conditional logic for preloading specific relations based on request parameters / model attributes.

```php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Netsells\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function preloads()
    {
        return $this->preload('avatar');
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'avatar_url' => $this->avatar->url,
        ];
    }
}
```

Now before the `toArray` is called, the `avatar` relation will be loaded for all the users in collection, so we've got full access to `$this->avatar` without issuing further queries.

[More examples can be seen in the tests.](tests/Integration/Resources/Super/FullLibrary/Preload/BookResource.php)


### Use callback method

Second way is to provide a callback which will be called with the resolved relations.

```php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Netsells\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'avatar_url' => $this->use('avatar', fn ($avatar) => $avatar->url),
        ];
    }
}
```

Now when `toArray` is called, the `avatar` relation hasn't been loaded yet. Our code in the `toArray` method will execute for all the users in collection, and each call to the `use` method will queue a relation to be loaded. We'll then resolve all the queued relations for all users in the most optimal way, and invoke the callback function with resolved relations.

[More examples can be seen in the tests.](tests/Integration/Resources/Super/FullLibrary/Callback/BookResource.php)


### Inline method

Third way is to use an inline shortcut which is very convenient for loading one or many nested API Resources.

```php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Netsells\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'avatar' => $this->one('avatar', AvatarResource::class),
        ];
    }
}

class AvatarResource extends JsonResource
{
    public function preloads()
    {
        return $this->preload('file');
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'width' => $this->width,
            'height' => $this->height,
            'url' => $this->file->s3Url(),
        ];
    }
}
```

In the example above, we have introduced a nested resource, where a UserResource embeds a separate AvatarResource.
Each resource is responsible for eager loading its own relations, and together all relations are loaded in most optimal way.

[More examples can be seen in the tests.](tests/Integration/Resources/Super/FullLibrary/Inline/BookResource.php)
