# Changelog

## Installation

1) Add the package to your project. 
```console
composer require webbundels/essentials
```

2) Migrate the database.
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
