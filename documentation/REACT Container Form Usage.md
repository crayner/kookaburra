# REACT Container Form Usage

### Implementing a Form
In Symfony, build for as normal and add the ___App\Form\Type\ReactFormType___ as a parent. (Use getParent() method)
####Settings in ReactFormType
* columns: Defaults to 2 columns
* template': Defaults to 'table'. This is the only choice at the moment.
* panels: Defaults to 1 panel. 
* target: Defaults to 'formContent'.  Use to load the container into the DOM with REACT. _(Note to self, is this required, as the Container should do the targeting.)_
