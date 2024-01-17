routes in drupla 8 or later may include placeholder
elements which deisgnate places where the url containns dynamic vlaues
by naming these placeholder, the system can upcast covert those vlaues 
to acutal object instances. For example if a node's base path is node/{node}
then '{node}' is a placeholder
the paramconverter system takes care of converting that parameter to a node object instance
automatically because there exists a content entity called node.
In the case that a route parameter matches the ID of an entity type
you do not need to implement the ParamConverter class
in the routing.yml simply write type:entity
entity:my_entity_type


