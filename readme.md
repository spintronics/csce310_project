download docker desktop

download mysql workbench

download node

install packages

> npm install

run the project

> npm run dev

open the model_professor.mwb file in mysql workbench

from the menu bar select Database > Forward Engineer

select the option to create a new schema

open a new connection to the database in mysql workbench

connection details:

- hostname: 127.0.0.1
- port: 3306
- username: root
- password: admin

## Kevin Brown

I created the following view:

```sql
CREATE VIEW `user_student` AS
select * from `user` join `college_student` on `user`.UIN = `college_student`.UIN;
```

I also made the username on the user table an index to make it easier to search for a user by username.
