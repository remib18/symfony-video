-- Setting the default admin user
insert into user(email, roles, password, firstname, lastname, is_banned)
values ('admin@admin', '["ROLE_ADMIN"]', '$2y$13$OFXCH/kbVK9h2czc.X7Rbeky6nV8E7GPHpykU9OkE7Rko9.YruZsC', 'Admin', 'Admin', false);

-- Creating the default homepage
insert into home_pages (label, markdown) values ('Setup', '# Finish setup

To complete the setup, please follow the instructions below.


## Login as the admin user

The default admin user is:
- username: `admin@admin`
- password: `admin`

## Change the default admin user by your own

This step is optional. You can keep the default admin user if you want, if you do so, please keep in mind that this might lead to security issues.

### Create your own admin account

- When logged as the admin user, click on the `Admin` link in the top left corner of the page.
- You should see a list of users.
- Click on the `Add User` button.
- Fill in the form.
- Make sure the `Roles` field contains `Admin` and press enter.

### Delete the default admin user

- Logout of the admin user.
- Login as your user with admin permission.
- Click on the `Admin` link in the top left corner of the page.
- You should see a list of users.
- Find the `admin@localhost` user and click on the `•••` button. Then click on the `Delete` link.
- Confirm the deletion by clicking on the `Delete` button.

## Update this homepage

This guide you are seeing is a markdown file. It is the default homepage. You can change it by following these steps:

- If you are not in the admin section, click on the `Admin` link in the top left corner of the page.
- Click on the `HomePages` link.
- Click on the `Add HomePage` button.
- Fill in the form. You can use markdown in the `markdown` field.
- Click on the `Create` button.
- Go to the `WebsiteSettings` page.
- You can directly edit the `Active HomePage` field
- Click on the `Save changes` button.

## Next steps

Once you are done with the setup, you can start using the application. We suggest the creation of a new user with the `Webmaster` role. This user will be able to create new pages and edit the existing ones.

');

-- Setting the default homepage
insert into website_settings (active_homepage_id, singleton_guard) values (1, 0);