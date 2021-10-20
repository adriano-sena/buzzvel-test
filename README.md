# Buzzvel Test 
> Repository created for technical testing on Buzzvel


This repository was created to delivery a package able to performs a HTTP request to the Buzzvel endpoint, procces the JSON data and return to the user a list of hotels ordered by the selected search critery(price or proximity). 
![](../header.png)

## Instalation 

```
composer require adriano-sena/buzzvel-test
```
## Configuration 

For configuration just use the composer install command to get all the dependencies needed to run the package. 

```
composer install

```


## Usage

Use the Search::getNearbyHotels( $latitude, $longitude, $orderby )  to get a list of hotels orderred by the  $orderby values: "pricepernight" or "proximity". 

Mandatory parameters:

● $latitude
● $longitude
Optional parameters:

● $orderby // Order by parameter should interpret one of the following
values “proximity” or “pricepernight”

If the $orderby parameter is not defined when calling the method, we’re
expecting to get the list ordered by proximity.


## History 

* v1.0.0
    * Initial version (olny the basic funtionality to get the formated hotels list)
## Meta

Adriano Sena – [@@NanoSena1](https://twitter.com/NanoSena1)


## Contributing

1. Make a _fork_ of the project (<https://github.com/yourname/yourproject/fork>)
2. Create a _branch_ (`git checkout -b feature/fooBar`)
3. Make a _commit_ (`git commit -am 'Add some fooBar'`)
4. _Push_ (`git push origin feature/fooBar`)
5. Create a new _Pull Request_
