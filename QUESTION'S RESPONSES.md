## QUESTION'S RESPONSES
1. Used technologies
> I used **Docker** for run project because I used it in development on my notebook.
> It's very comfortable and not need install anything on the personal notebook.

> I used **PHP** without framework because is a faster for this task.
> On the work I use the Symfony framework.

> I used **MySQL** as storage for User entity

> I used **Redis** as token's storage because it decreases DB size(not need save temporary datas)
> and make API faster

2. PUT, POST difference
> The difference between PUT and POST is a matter of semantics.
> PUT method is called when you have to modify a single resource,
> while POST method is called when you have to add a child resource.

3. Make Api faster
> DB indexes, Cache tokens

4. Use ELK for logging Errors and Exceptions
5. Caching Endpoints(This functionality is implemented) 
> /token - result token is putting to the Redis

> /account - is checking Redis for valid token