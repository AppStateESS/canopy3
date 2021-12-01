# Roles and Permissions

Roles in Canopy3 define a user's status in a system. Permissions define what each Role is allowed to do. Each role has a set of permissions. When an user is assigned a Role, the role's permissions apply to them as well.

## The Role Trinity
There are three main roles in the ```AbstractRole``` class. In order of least permissions to most: guest, logged, and admin.

When a Role extends the ```AbstractRole```, it based on one of these three.

### The Guest Role
Anyone on the site who has not logged in, is a guest. This is the most basic role. All guests can do and see the same things. While a logged or admin user can possess many roles, the guest role is a singularity. Only by logging in can a guest user have other roles.

While this role is humble, it is also the most dangerous. You must prepare your code for an assault by the lowly guest as they have no barrier to input.

### The Logged Role
Now our user starts having some status. The logged user has passed an authentication check and is welcome into the site. They may see and manipulate things the guest is denied.

A user can have multiple logged roles. Using a blog as an example, a user may have a logged role containing reading permissions for a private channel.  Meanwhile guest users aren't allowed this privilege. This same user may also have a posting role that allows them to submit a comment to the channel. The blog developer can define both roles like so:
```
class BlogReaderRole extends Canopy3\Role\Logged {}
class BlogPosterRole extends Canopy3\Role\Logged {}
```
The developer could then just check if the ```CurrentUser::hasRole('BlogReaderRole')``` to determine if they

### The Admin Role
Now we are talking. The Admin role gives the user the power to change the site! A blog admin could create a channel or remove it entirely:

```
class BlogAdminRole extends Canopy3\Role\Admin {}
````
Or maybe the blog developer has different tiers of admins. One admin role allows the creation of channels and one role allows their removal. It is up to the developer to determine the specificity.

## How Roles work with Controllers
When a user lands on a URL, the dashboard or plugin determines how to route them.

Let's use a user with the BlogReaderRole as an example. For example, let's use the URL ```sitename.com/p/Blog/Channel/3```

The controller expects a RESTful GET command to view the Channel with id=3.
We have three role subcontrollers for Channel.
The Guest role controller would load (or check a property of) the Channel with id=3.
If not Guest accessible, then the user is given a warning.

In this case, the user has the ```BlogReaderRole``` however, so that Role's subcontroller would let them view the Channel.

Most likely, if the user had the Admin role, they would be able to view it as well as all other Channels.

## Permissions

Permissions decide what a Role is allowed to do in a system. A permission cannot be assign a permission to any role lower than its default role type. In other words, a permission for deleting a Channel would have an Admin role and could not be assigned to Logged Role. You could, however, have a view permission with a Guest role default. You could then give assign that permission to an Admin only. So if Channel 4 is an Admin only channel, then that view permission would be restricted to admins.
