When drupal development it won't be long before you encounter the word
'entity' and the Entity API . But what are entities in Drupal ?
How can you use them to build your site ? When should you use the Entity API? 

This tutorial will explain Drupal's Entity system from a high level. We'll look at:

- The main problems the Entity system solves
- Key terms you should know
- Key concepts we'll explore as we dive into Drupal's Entity API

By the end of this tutorial you should be able to explain the problems that the Entity 
API solves , and when you should use it in your own code.

# Goal 

Provide an introduction to the Drupal Entity API.

# Prerequisites

# What are entities ?

Entities are the basic building blocks of Drupal's data model.
They make up, in one way or another, all of the visible content a user interact
with on a Drupal-powered site. There are several types of entities included in Drupal core
that make up both the configuration and content of a default installation. It's importa nt to understand
the basic difference between these two types of entites before we really dig in further

Configuration entities are objects that allow us to store information (and default values) 
for configurable settings on our site. Examples of configuration entites in core
include image style, user roles , and displays in views.
Configuration entities can be exposed via core's configuration management system.
They can also be used to provide default configuration used during the installation 
process or when a new module is enabled. Configuration entities support translation
but they cannot have user-configured fields attached to them. The data structure of a configuration
entity is limited to what is provided by module code.

Content entities are configurable , support translation and revisions , and allow
additional fields to be attached for more complex data modeling.
Content entities included in core include node,taxonomy terms, blocks and users.

Often you will find entity variants that come in pairs. The block module, for exmaple 
provides configuration entities to define custom block types and content entities 
to provide the actual content of custom blocks.

# Key terms in Drupal's Entity API

Now that we have a little better idea of what Drupal means by the word entity, 
let's look at some other key terms that wel'll need to know in order to understand Drupal's entity API.

## Bundles(Пакети)

Bundles are another generic noun,used to describe containers for a sub-type of a particular
entity. For example nodes,  are a type of entity.
The node entity has a bundle for each content type (ie: article,page,block page.)
Taxonbomy is another entity type, and each individual vocabulary is its own bundle.
Bundles provide an organizational abstraction layer that allows for differences in field definitions and 
configutions between entity sub-types. For a particular entity type all bundles will have the same
base fields but will have different bundle fields .

# Fields

Fields consist of individual ( or compound) data elements that make up the details of
the data model.If you're trying to build a photo gallery, your node type will need some method of collecting images.
An image field would be handy in this case.
Drupal core provides several different types of fields including boolean, decimal, float, integer ,entity reference, link , image
email, telephone, and several text fields.
Fields, in turn, are built on top of the actual data primitives in Drupal, Typed DATA API.
Fields can be added to content entities and field configuration will vary between bundles of the same 
entity type.

# Plugins 
In short, plugins provide developers an API to encapsulate re-usable behavior.
Plugins are used throughout Drupal core and you'll be exposed to several of them while working with the Entity API.
We have several tutorials that cover plugins in more depth.

# Annotations
Annotations are another elements of Entity API used throughout Drupal core.
Annotations are specially formatted code comments that are parsed to provide metadata
information about particular PHP classes to Drupal. The Entity API uses
annotations to discover which classes to load for a particular entity type ( among other things).

# Handlers

If you've worked with the Entity API in previous versions of Drupal you're probably
already familiar with controllers.Handles are Drupal's latest equivalent of Drupal 7's controllers.
Handlers are responsible for acting on and with entites.
They help manage things like storage, access, building lists and views of entities, and the forms 
required for creating , viewing, updating and deleting entities.

Entities are the building blocks that make up just about everything on a Drupal site.

Regardless of whatever entites provide configuration information or content, they are absolutely crucial
to Drupal's data model.

# Configuration entities

- Custom block types
- Views
- Menu
- Role

# Content entites
- Node 
- User
- Taxonomy
- Blocks

Prior => предишен

From the names of the methods alone,it's apparent that configuration entities
main functionality has to do with information storage and synchronization.
The main functionality built into content entities, on the other hand, has to do with fields,
translations, validation and revisions. It's worth diving deeply and reading the
source code of both ConfigEntityBase and ContentEntityBase classes to get a sens of the functionality, similarities and differences
between them.


Content entity basics

As we've already seen from their interfaces, unlike configuration entities ,content 
entities are field able. Content entities can have fields that are shared among
all entities of a given type. These are called base field .Fields that are unique among
the sub-type (or bundle) are called bundle fields. Let's take a closer look at node entities to see
this in action

The node entity type is defined by the `node` class.
The base fields for node are defined in the node::baseFieldDefinitions method



Load entities 
The first hook we'll look at are hook_entity_load and hook_entity_storage_load
hook_entity_type_load. These hooks allow us to interact with entity types  as they're
loaded by the api.As such it's extremly important that these implementation
take performance considerations into account since this code could be executed hundres of thousands of a time per page load.
If you're adding additional data onto content entities you should use
hook_entity_storage_load because the result of that hook are cached.

## Interact with an entity before it is loaded

hook_entity_preload
A new hook as of 8.7 has been introduced that allows you to interact with an entity before it is loaded.

Some modules need to act before an entity is loaded, and swap out the default revision with a different one.
For example, the Workspace module swaps the default revision with a workspace specific version, if one exists.

## Interact with entities prior to display

You may also find youself with a need to interact with entities just prior to their display
Especially in the instance where the additional data you need to apppend to your entity is itself an entity , you may implament
hook_entity_prepare_view

# Entity Insert 

Similar, but slightly different from the create hook is 
The insert hook responds to the creation of a new entity once the actual data has been
stored. This hook can respond to, but can not change the data stored along with a 


# Typed Data in Drupal

There are a few different elements that come together to create
Drupal's Type Data system. Each data type is a plugin implementation.
The plugins that implements typed data are managed by a service
called the typed_data_manager. Each one of these typed data objects encapsulated the actual data,
some metadata and provides a inified mechanism for validation.
While typed data gives us a unified method of getting, settings and validating values, 
the metadata is available from a typed data object depends on something called the data definition.
The class that is used to provide the data definition
is specified for every data type.

The data types provdied by core can be found in
/core/lib/Drupal/Core/TypedData/Plugin/DataType
Let's take a look at the code that describes the email data type.
This data type is specified by annotation and a class in the file
Email.php in this directory.
@DataType(
id
label
constraints
)
The actual implementation class here isn't too interesting.
The key thing to note here is the plugin annotation @DataType
The data type defines an id , a label, and constraints. Data type annotations may also
specify the class responsible for the data definition.
This is especially common among the more complex typed data objects like
ItemList.Depending on the implementation details there may also be a related
class in the core/lib/drupal/core/typeddata/type directory
for our email example it inherits from the StringData class which in turn extends 
the primitivebase data type.

take a look at some of the other data types defined in this directory.
some of them have additional methods that add additional metadata to the typed data object
the timespan data type in particular implements some of this additional functionality to allow the getting and setting 
of duration values.

As you become familiar with the different types of Typed data you might
notice that are 3 main building blocks
primitive, complex and list data

Primitive data 
Typed data that inherits from or extends from PrimitiveBase class in some way
can be considered primitive data . The email data type we look at earlier falls into this category 
primitive data types are often just special cases of integers or strings
They are types of data that may have a particular format of specification like an email or a url , but they don't require much additional metadata
metmetadata or functionality

TYped data extends the primitive data type will have getValue()
and setValue methods for working with the values .
 There is also a getDefinition() method that can be used to retrieve additional information
about the typed data you're working with .

Complex data
Complex data represent data that contain additional named properties.
These named properties can themself represnet typed data object.s
Because of this complex data types can be considered similar to a map or an associative array.
Drupal's Field API uses complex data types to represent the data store by filed items
You can see the implementation details of this in the FieldItemDataDefinition class.

Complex typed data will have get() and set() methods



List data
The list data type represent ordered list of items (all of the same type). Since lists are
ordered data they can be used to sort and organized the items they contain .
A list data type could be used to storag e RBG avalues of a oclor.
This data type wouyld be compoosed of 3  interger, each betyween 0 and 255 , that rprepsent a red ,g reeen eor blue valuie


In additipon to the expected get and set mwethods lis ttyped sdata will
have an offsetGet method that helps us determine which posstion in the order a 
particular piece of data has


Using the typed data manager

Once we have described our typed data to drupal we can then take advatntage of the unified interface we've mention fo-r interacting with
it. Le'ts say we'd like touse the serialization api to return
some json to a web service.
Without the typed data api we'd have to write code that knows how to encode
JSON, format strings, and iterate over potentially complex objects.
Thanks to the typed data definitions, Drupal knows how to return values based on paritcular data 
type we're working with

$listDefinition = \Drupal::typedDataManger()->createListDataDefiniton('email;)

$list = \Drupal::typedDataManager()-craete($listDefiniton,[])
$serializer = \Drupal::service('serializer');


In this tutorial, we saw how Drupal used the typed data api to provide a inifued interface for interacting with differnet types of data objects.
Using typed data we can rely on getting setitng and validation values without worrrying tabout the implemntiaon details
surrounding the type of data we're working with.

Further your understanding.

The Typed Data Api in drupal helps additional functionality

unlike other programing languages php is loosly typed.
This means thhat there is no consistent native mechanism for knowing
what