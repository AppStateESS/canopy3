# Command pathing and System terminology
This manual discusses Resources, Factories, Controllers, and Roles and how we program ```systems``` (plugins and dashboards) around these ideas.

## Resource
The term Resource and the thought behind it is carried over from Canopy. Simply, a Resource is an object with hooks allowing easier data manipulation.

Unlike Canopy's old Resource class, Canopy3 is a lighter construct. Canopy used Variable objects for each property. Canopy3's resource's properties are simple types (int, string, bool, etc.). Behavior is directed by the abstract and manipulated by the ResourceFactory.

## Factory
A Factory is OOP is a class that creates an object. Our Factory classes do this, but we stretch the definition a little further. A Factory class in Canopy3 assists in not only the construction (if at all, you may instantiate outside the Factory) but also in the saving, changing, and removal of the Resource.

## Controller
A Controller guides the site visitor to the proper process determined by the URL. Each dashboard will have one main controller. It will direct the user dependent on the command and their Role. Complex ```systems``` may require subcontrollers per Role and/or Resource.

## Role
The user's Role determines their access to processes. For example, a role defined as ```User``` allows a Resource to be viewed. An ```Admin``` Role allows that same resource to be deleted. A ```User``` trying to access the deletion control, will be denied.


## Bringing it together
When creating a dashboard or plugin, we follow these assumptions:
- the Resource will contain the data,
- the Factory will allow you to manipulate the data,
- the Controller will access to the Resource,
- the Role determines the Controller permissions

We find it best to group these classes in directories reflecting their namesake.

Example:
```
MyDashBoard\Resource\Widget
MyDashBoard\Controller\WidgetAdminController
MyDashBoard\Controller\WidgetUserController
MyDashBoard\Factory\WidgetFactory
```
An alternative Controller namespace could be:
```
MyDashBoard\Controller\User\WidgetController
MyDashBoard\Controller\Admin\WidgetController
```

Ultimately, you should be able to look at a RESTful URL and figure where the Controller lies.

For example, here is an URL that goes to a form:

```mysite.com/d/MyDashboard/Widget/3/edit```

From this URL and knowing my Role (I'm an admin) I would look in:
```Dashboard\Widget\Admin\WidgetController::editHtml()```

I look in ```editHtml``` because I know the response was HTML.

Note: you don't have to handle the Response control this way -  it's just an example.