# Essentials package

## Installation

1) Add the package to your project. 
```console
composer require webbundels/essentials
```

2) Add the needed arguments to your .env file
```console
# The token that the package will use to send requests to github to gather info.
GITHUB_TOKEN='github_YOURTOKEN'
# The owner of the repositories, for example: github.com/{owner}/{repo}
GITHUB_OWNER='webbundels'
# The repository(ies) of which the package should gather commits from seperate each repository with a ','
GITHUB_REPOSITORIES='jongfresh-app,jongfresh-online'
```

4) Migrate the database.
```console
php artisan migrate
```

## View permissions
1) Add the methods 'getChangelogViewableAttribute' and 'getDocumentationViewableAttribute' to your user model.
2) Write logic in this method that determines if the user can view the changelog/documentation page.

```php
public function getChangelogViewableAttribute() :bool
{
    return $this->can('view_changelog');
}

public function getDocumentationViewableAttribute() :bool
{
    return $this->can('view_documentation');
}


```

## Edit permissions
1) Add the method 'getChangelogEditableAttribute' and 'getDocumentationEditableAttribute' to your user model.
2) Write logic in this method that determines if the user can edit the changelog/documentation page.

```php
public function getChangelogEditableAttribute() :bool
{
    return $this->can('edit_changelog');
}

public function getDocumentationEditableAttribute() :bool
{
    return $this->can('edit_changelog');
}
```
