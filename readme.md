download docker desktop

download mysql workbench

download node

open the model.mwb file in mysql workbench

from the menu bar select Database > Forward Engineer

select the option to create a new schema

open a new connection to the database in mysql workbench

connection details:

- hostname: 127.0.0.1
- port: 3306
- username: root
- password: admin

execute this stored procedure:

```sql
call `setup`;
```

you can use the following for development (auto update)

> npm install

> npm run dev
