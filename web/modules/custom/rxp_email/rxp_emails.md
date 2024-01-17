an asset library is a bundle 
of ccss and or javascript files that work together to provide a style 
and functionality for a specific componenet.
They are frequently used to isolate the functionality
and styling of a specific component.
They are frequently used to isolate the functionality and styling of a specifc 
component, like the tabs displayed at the top of each node , into a reusable library. If you want to include css and
and/or javascript in your Drupal theme or module you'll neeed to declare an asset library
that tells Drupal about the existence, and location, of those file .And then attach the library to a page,
or specific element, so that it gets loaded
when needed.

In this tutorial we'll :
Define what an asset librariy is.
Explain why asset libraries are used to include JavaScript and css files.
Look at some example asset library definitions.

By the ened of tutuorial you should be able to define what asset libraries are , and
when you'll need to create one.

Goal
Explain what asset libraries are and the role they play in Drupal theme.

Prerequisites 

The problem with loading css or javascript files in Drupal

The asset library system exists to make it possible for Drupal to:
Include the only css and js files required for a specific page.
Vary what is included depending on the content of the page.

This approach reduces the overall size of the page which can have a performance
impact. It speeds up rendering by ensuring that the browser doesn't need to spend time processing
css and js files tha'ts never used.

What are asset libraries ?
An asset library is yaml data structure inside a 
specified one or mode css and js files , and their settings, bundled together under a uniquely identified library name.
Once the library has been defined adding it to a page , or attachking it to a particular type of element.
is done in the same fashion regardless of the contents of the library.
This means there is now one unified mechanism for adding CSS and JS whatever it's been
added in a module or a theme.

As a theme developer you'll define new asset libraries that points to the location of your
custom css and js code.
And then tell Drupal when to include the library by associating it with template
file, a specific render element, or globally for every page using the theme.

The classy libraries 
We can take a look at some libraries provided by the modules and themes in Drupal core
to see examples of this in practice
The classy theme provides a handful of libraries
We can see here that the classy theme provides several base css components 
for things like breadcrumbs buttons ,item lists, and pager among other. All of these asset libraries are composed entirely
of css files.

Core libraries
We can see more complex examples in core.libraries.yml
This file has an example of Javascript library as well as one that provides a dependency on another
library.

New asset libraries can be defined by either modules or themes.
In order to define a new asset library you need to create the requisite
css and javascript files and a new theme_name.libraries.yml file
that aggregates them together and provide metadata about the library itself
and any dependencies.

In this tutorial we'll 
look at the structure of a *.libraries.yml file and demonstrate how to combine
a couple of css and js files together into an asset library 
that cna be used in a theme or a module
Look at how one asset library can declare that it is dependent on another in order to
ensure the asset from the dependcy are loaded as well

By the end of this tutorial you should know how to define a new asset library in either a module 
ro a theme.

Goal

Define a new asset library named 'retro' that includes both css and js assets.

    
Creating the retro library

The example library we're going to create is a custom asset library 
called retro that we can use to add some pizzaz to our site.
The first thing we need to do is to decide if this asset library belongs to a theme
or to a module.
In our case,we're going to add this asset library to our theme.
IF you havn't create a theme before give this a use of base theme a try.

Drupal follows a smacss style categorization and css files 
are loaded first based on their category
and then by the order they are listed within a given category
The categories are as follows:

base: -> css/reset normalize plus html element styling
layout -> macro arrangement of a page ,including grid systems
components -> discrate , reusable UI elements
state -> styles that deals with clien-side changes to components
theme -> purely visual styling look and feel for a component