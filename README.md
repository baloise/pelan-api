# Pelan-API
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/baloise/pelan-api/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/baloise/pelan-api/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/baloise/pelan-api/badges/build.png?b=master)](https://scrutinizer-ci.com/g/baloise/pelan-api/build-status/master)

API for [Pelan](https://github.com/baloise/pelan/) by Baloise.

This API is made for the Pelan-UI made by [Elia Reutlinger](https://github.com/erleiuat).
The development of the UI was part of his final project (IPA, Individuelle Praktische Arbeit) at the near end of his apprenticeship @Baloise.

You can find the API-Docs [here](https://documenter.getpostman.com/view/6073079/S17jVXSG).

<b>Check out the [IPA-Docs](https://github.com/baloise/pelan/tree/master/docs/IPA) (docs/IPA) to get a detailed documentation about Pelan.</b>

- PHP (7.3+)
- Authorization using JWT

## Setup (Using XAMPP)
1. Get and install XAMPP (with PHP 7.3+) from [here](https://www.apachefriends.org/).
2. Clone this repo and move the files from `/api` into XAMPP's root/htdocs folder.
3. Open XAMPP and start Apache and MySQL from the control panel.
4. Go to phpMyAdmin (click `Admin` in the control panel).
5. Go to `Import` and select the SQL-Dump at `docs/sql/pelan.sql` OR the one at `docs/sql/demo/pelan_with_demodata.sql` including some data for testing.
6. Open [localhost](http://localhost/) to see if everything is working.

7. Get [Postman](https://www.getpostman.com/) or [Pelan](https://github.com/baloise/pelan/) to make requests. You can find available endpoints at the [Postman API-Docs](https://documenter.getpostman.com/view/6073079/S17jVXSG).

## Setup (Using Docker)
Comming soon
