# pkg_agosm
Openstreetmap Modul

> Attention, this extension is not compatible with 
[plg_fields_agosmmapwithmarker](https://github.com/astridx/plg_fields_agosmmapwithmarker). 
The two extensions can be installed together. They may not be displayed on one page at the same time.

# Quickstart

## Module

### AGOSM Module 

1. Install this package via Joomla! installer. 
Please check after the installation if the module is correcly installed. 
Open the menu `Extension | Modules` and click the toolbar button `new`. 
In the appearing list should be the entry `AGOSM Module`.

![b1](https://user-images.githubusercontent.com/9974686/51428134-82b52c80-1c00-11e9-800a-26c0f29e7583.png)


2. Create the module
Click the entry `AGOSM Module`. In the appearing form you have to enter at least 
the title and the position. And you have to select in the tab `menu assignment` 
on which menu items the map should be displayed. 

![b2](https://user-images.githubusercontent.com/9974686/51428135-82b52c80-1c00-11e9-919f-5c9fb75d5ad7.png)

3. Check your map in the front end
If you set one standard pin in the tab `special pin configuration` your map 
will look like in the next image.

![b3](https://user-images.githubusercontent.com/9974686/51428136-82b52c80-1c00-11e9-947f-5cd243bfdc40.png)

Voila!

#### Routing to address - How to find us 

A popular feature of this extension is 'Routing to address'.  

1. Fill the information in the tabulator 'Routing to address'.
- If you like to use address suggestions you should use a positon on the top.  
- Per default [OSRM](http://project-osrm.org/) is used as router. OSRM is sometimes busy. If you like to 
use mapbox you have to get an access token: https://docs.mapbox.com/help/how-mapbox-works/access-tokens/

![a1](https://user-images.githubusercontent.com/9974686/51428132-82b52c80-1c00-11e9-96ba-b7a33348e6b5.png)

2. In front end you see a text field

If you enter a address in the text field in the front end and click enter you see
a route to the address you entered in the back end.

![a2](https://user-images.githubusercontent.com/9974686/51428133-82b52c80-1c00-11e9-8d4d-afee1c2e4a3a.png)

3. If you activate the option suggestions, you will see a 
[Algolia Places autocompletion menu](https://community.algolia.com/places/documentation.html#demo).

![a1](https://user-images.githubusercontent.com/9974686/64909626-f1567580-d70e-11e9-9c9d-fcd70ac48327.png)

#### Routing with Mapbox

You will find the option to enable MapBox Routing in the backend. 
For this you need a [key](https://docs.mapbox.com/help/how-mapbox-works/access-tokens/).

![a4](https://user-images.githubusercontent.com/9974686/64909623-f0bddf00-d70e-11e9-95eb-00a7b9591427.png)

If you activate the option you will see a collapsed control. 
When you roll your mouse over the controller, it opens.

![a2](https://user-images.githubusercontent.com/9974686/64909625-f0bddf00-d70e-11e9-8612-ed8849652a5f.png)

To select starting point and destination point you have several options. See following picture. 
Thank you for the hint with the buttons to [jojo](https://github.com/jojo12)

![a3](https://user-images.githubusercontent.com/9974686/64909624-f0bddf00-d70e-11e9-90c7-b2e78b0b7f64.png)

The result after clicking on Return should look something like this.

![a5](https://user-images.githubusercontent.com/9974686/64909622-f0bddf00-d70e-11e9-9f26-9b785e00fbd2.png)

#### Show markers on a map that link to an article - you can enter the coordinate in a Custom Field

##### Custom Field of the type text

The next picture shows what the map might look like. If the markers are too close together, 
they will be clustered. If the resolution of the card fits, the marker will be displayed. 
A click on the marker opens a popup. In this popup there is a link to the post - link text is 
the title of the article.

![Home](https://user-images.githubusercontent.com/9974686/58746148-263e0580-845b-11e9-8b30-374c4256f46e.png)


Do you want to display a marker on a map that contains a link to an article? 
Then create a Custom Field of the type text titled `lat, lon`. 
Be sure to write the title in the same way - the space after the comma is important. 
Any article with the custom field filled with a correct coordinate will be displayed on the map. 

![Articles  Fields   test   Administration](https://user-images.githubusercontent.com/9974686/58746149-26d69c00-845b-11e9-920b-f5309b98ff37.png)

If you want to use different colors or icons, you can fill the custom field with the title `lat, lon` as 
follows: `lat,lon,markercolor,iconcolor,icon`. 
As color, you can choose red, darkred, orange, green, darkgreen, blue, purple, darkpurple and cardetblue. 
For the icon you can choose the name of the Font Awesome Icon. 
For more informations see https://fontawesome.com/icons?d=gallery.

When you enter `50.150, 7.158, red, green, home` you will see an icon as shown in the next picture.

![Custom Marker](https://user-images.githubusercontent.com/9974686/59145384-7250f300-89e3-11e9-96c8-772a0f63ff7e.png)

##### Custom Field agosmsmarker and agosmsaddressmarker

Instead of the text fields, there are now two special custom fields where you 
can choose all options more user friendly.

###### agosmsmarker 

Create the Custom field agosmmarker

![omarker2](https://user-images.githubusercontent.com/9974686/65074589-7357d000-d995-11e9-998c-fe0ad2ad413f.png)

Fill the Custom Field agosmsmarker while editing an article. You can choose many options, 
but you need to know the correct coordinates. 

![omarker1](https://user-images.githubusercontent.com/9974686/65074590-7357d000-d995-11e9-9ac9-609ed6bef35a.png)

###### agosmsaddressmarker

The Custom Field agosmsaddressmarker calculates the coordinates for a given address. 
For this you need another Custom Field whitch contains the address.  

![amarker1](https://user-images.githubusercontent.com/9974686/65074594-7488fd00-d995-11e9-81f8-dda8e011f27c.png)

Fill the Custom Field agosmsaddressmarker while editing an article. 
You do not need the full address. You can enter only 
the name of the city if you want a marker in the center of a city.

![amarker2](https://user-images.githubusercontent.com/9974686/65074593-73f06680-d995-11e9-8eb7-091f34e12079.png)


##### Options common to all custom fields

The next picture shows where you can activate the option in the module.

![Modules  AGOSM Module   test   Administration](https://user-images.githubusercontent.com/9974686/58746150-276f3280-845b-11e9-9678-7521c89fbe80.png)

Maybe you do not want to show the field lat lon in the frontend. 
Just select the option `Do not automatically display` in the Custom Field options.

![Beiträge  Feld bearbeiten   Administration](https://user-images.githubusercontent.com/9974686/59145428-19358f00-89e4-11e9-8446-6079f655e0d8.png)


#### GPX - Fileupload

Please choose the layout `upload` 
if you want to allow a user to upload a GPX file to the server and view it on the map.

![Module  AGOSM Modul Administration](https://user-images.githubusercontent.com/9974686/59145427-189cf880-89e4-11e9-8900-4a4b7f30e44e.png)

### AGOSM Search Module 



## Component

1. Open the component via `Components | Agosms` and click the toolbar button `new`.

![b4](https://user-images.githubusercontent.com/9974686/51428137-834dc300-1c00-11e9-873f-34d3e4ad3dc3.png)

2. Create one entry. At least you have to fill the title and the coordinates 
in the tab `Module Agosms - Marker Options`

![b5](https://user-images.githubusercontent.com/9974686/51428138-834dc300-1c00-11e9-9a31-e6e0bca79832.png)


3. Go back to the module and set the option `show pins from component` to `yes`.

![b6](https://user-images.githubusercontent.com/9974686/51428139-834dc300-1c00-11e9-92bb-0c517883da5f.png)

4. Adjust the map
In the tab `Map configuration` you can set the zoom and the coordinates that 
should be shown.

![b7](https://user-images.githubusercontent.com/9974686/51428140-834dc300-1c00-11e9-909c-fcc57f855849.png)



# Options



# All parts of this extension

## Module: Fields - Agosm  
It is the main part of this extension.

## Component - Agosms  
The Component is used for showing a view for selecting the coordinates.




# FAQ
## What is [OpenStreetMap](https://www.openstreetmap.org)?
OpenStreetMap (OSM) is a collaborative project to create a free editable map of the world. 
Rather than the map itself, 
the data generated by the project and saved in a datase is considered its primary output.

# Support and New Features

This Joomla! Extension is a simple feature. But it is most likely, that your requirements are 
already covered or require very little adaptation.

If you have more complex requirements, need new features or just need some support, 
I am open to doing paid custom work and support around this Joomla! Extension. 

Contact me and we'll sort this out!

[![](https://www.paypalobjects.com/en_US/DK/i/btn/btn_donateCC_LG.gif)](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=KQMKUVAX5SPVS&source=url)





