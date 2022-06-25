<p style="text-align:center">
 <img src="https://banners.beyondco.de/Larapi.png?theme=light&packageManager=composer+require&packageName=andreapollastri%2Flarapi&pattern=plus&style=style_2&description=Filter+your+Laravel+API+like+a+Pro&md=1&showWatermark=0&fontSize=125px&images=filter&widths=250&heights=250" alt="Larapi">
</p>

# Larapi
### Filter your Laravel API like a Pro
[![Tests](https://github.com/andreapollastri/larapi/actions/workflows/run-tests.yml/badge.svg)](https://github.com/andreapollastri/larapi/actions/workflows/run-tests.yml) [![PSR-12 Standard](https://github.com/andreapollastri/larapi/actions/workflows/php-cs-fixer.yml/badge.svg)](https://github.com/andreapollastri/larapi/actions/workflows/php-cs-fixer.yml)
<br><br>

## Introduction
This package allows you to filter Eloquent models via API using querystring requests.
<br><br>

## Getting Started

- Install Vendor:

```shell
composer require andreapollastri/larapi
```

- Larapi support two controllers resources methods, index and show. Put this code into your controller methods:
```php
    public function index(Request $request, Larapi $larapi)
    {
      $data = $larapi->filterIndex('\App\Models\<YOURMODEL>', $request)

      if(!$data) {
        abort(422);
      }

      return response()->json($data);
    }

    public function show(Request $request, Larapi $larapi)
    {
      $data = $larapi->filterShow('\App\Models\<YOURMODEL>', $request);

      if(!$data) {
        abort(422);
      }

      return response()->json($data);
    }
```

- If response is NULL it means that there is a Query issue, check your parameters (and manage Bad Request error).

- Remember to use {id} parameter for show routes

- Model hidden fields are "not allowed" in query filters

- You can define a set of optional "base" custom "where" filters using $customFilters parameters, e.g.:
```php
    $data = $larapi->filterIndex('\App\Models\News', $request, ['is_online' => true]);
```
- You can define a Laravel native cache layer, e.g.:
```php
    $data = Cache::remember(sha1(url()->full()), 3600, function () use ($larapi, $request) {
      return $larapi->filterIndex('\App\Models\News', $request);
    });
```
<br><br>

## Usage
<br>

#### Index Filters 

| Parameter | Description | Example |
| :--- | :--- | :--- |
| **where** | Field equal parameter | ?where[firstname]=John&where[lastname]=Doe | 
| **whereNot** | Field not equal parameter | ?whereNot[id]=123 | 
| **like** | Field like %value% parameter | ?like[city]=erlin | 
| **startsWith** | Field startsWith value% parameter | ?startsWith[city]=Ber | 
| **endsWith** | Field endsWith %value parameter | ?endsWith[city]=in | 
| **higher** | Field higher than parameter | ?higher[age]=25 | 
| **lower** | Field lower than parameter | ?lower[distance]=250 | 
| **order** | Fields order parameter (comma separator, pipe asc or desc to define direction - asc by default) | ?order=price,brand|desc,category_id|asc,name (name and price are ASC by default) | 
| **rand** | Get random records | ?rand=true | 
| **only** | Select only fields parameter (comma separator) | ?only=firstname,lastname | 
| **with** | With relations parameter (comma separator) | ?with=categories,author,tags | 
| **has** | WhereHas Related Subquery using table, field and value | ?has[comments][author_id]=7 | 
| **hasLike** | WhereHas Like Related Subquery using table, field and value | ?haslike[products][description]=camping | 
| **paginate** | Number of record in paginate option | ?paginate=10 | 
| **page** | Number of current page in pagination (only in paginate mode) | ?page=3 |
| **limit** | If you are not using paginate option, you can limit the result set using limit parameter | ?limit=25 | 

<br>

#### Show Filters 

| Parameter | Description | Example |
| :--- | :--- | :--- |
| **only** | Select only fields parameter (comma separator) | ?only=firstname,lastname | 
| **with** | With relations parameter (comma separator) | ?with=categories,author,tags | 

<br><br>

## Security Vulnerabilities and Bugs
If you discover any security vulnerability or any bug within larapi, please open an issue.
<br><br>

## Contributing
Thank you for considering contributing to this project!
<br><br>

## Licence
Larapi is open-source software licensed under the MIT license.
<br><br> 

### Enjoy larapi ;)