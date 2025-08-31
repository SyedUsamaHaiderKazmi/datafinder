
## _Usage Guide_

The following instructions will guide you through the necessary steps to implement and customize the data table and filter functionality within your Laravel project.

* #### _Basic Setup_

To get started, you need to include the necessary blade components in the relevant view where you wish to display the data table and filters (typically your index or a specific view for the module).

###### **_Include the Data Table and Filters:_**

In your blade view, add the following includes:

```php
// for filters
@include('datafinder::filters', ['config_file_name' => 'YOUR_CONFIG_FILE_NAME'])
// for datatables
@include('datafinder::datatable', ['config_file_name' => 'YOUR_CONFIG_FILE_NAME'])
```
###### **_Explanation:_**

| Key          | Description                                                                                                                                                                          |
| :----------- | :----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `config_file_name` | key should correspond to the configuration file name you've set up for the data table in the module you're working with. |


> **Note:** These includes will render the user interface (UI) for the package, displaying both the filters and the data table. The look and feel of the UI are customizable and can be adjusted by the end user as needed.

---

* #### _Advance Usage_


In some cases, you might need to pass dynamic data such as values from the database into the filters instead of using hardcoded values in the configuration file. Since you cannot directly utilize Eloquent models within the configuration file, the package provides a way to parse custom PHP variables.

###### **_Passing Custom PHP Variables:_**

To handle complex filter cases, you can pass dynamic data (e.g., values from the database) to the filters using the phpVariables key. Here’s an example of how to pass custom variables to the view:

```php
@include('datafinder::filters', [
    'config_file_name' => 'YOUR_CONFIG_FILE_NAME', 
    'phpVariables' => [
        'languages' => $languages, 
        'countries' => $countries,
        'hasValue' => true,
        'textValue' => 'Your Text Value',
    ]
])

```

| Key          | Description                                                                                                                                                                          | Value |
| :----------- | :----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | :---- |
| `phpVariables` | This key is used to pass custom PHP variables (e.g., an array of languages or countries) to the package. The package will access these variables to populate the filters dynamically | N/A   |

In your config file, reference the dynamic PHP variables passed from the view. For example:

```php
[
    ...
    [
        ...
        "name": "Languages",
        "value_type": "PHP_VARIABLE",
        "value": "languages",
        ...
    ],
    [
        ...
        "name": "Countries",
        "value_type": "PHP_VARIABLE",
        "value": "countries",
        ...
    ]
    ...
]

```

###### **_Explanation:_**

| Key        | Description                                                                                                        | Value |
| :--------- | :----------------------------------------------------------------------------------------------------------------- | :---- |
| `value_type` | Set this to `PHP_VARIABLE` to indicate that the value for this filter is being passed dynamically from PHP variables | N/A   |
| `value`      | The key that corresponds to the PHP variable passed in the view (e.g., languages, countries).                      | N/A   |

---

* #### _Custom Usage_

In scenarios where the default configuration of **DataFinder** does not meet the complexity or flexibility required by your application, the package offers a **Custom Usage Mode**. This mode allows you to define your own **[DataTables](https://datatables.net/)** initialization while still benefiting from DataFinder's robust features such as:

>- Unified filter strategy
>- Optimized, responsive layout
>- Backend logic integration
>- Seamless **[DataTables](https://datatables.net/)** setup


###### **_When to Use Custom Usage:_**

>- Your application requires a non-standard **[DataTables](https://datatables.net/)** setup.
>- You have specific backend endpoints, columns, or behaviors not covered by the default setup.
>- You still want to take advantage of DataFinder's filtering, layout, and integration logic.

###### **_How to Enable Custom Usage:_**

To enable custom usage from the frontend, include the DataFinder blade component with the following **required parameters**:

```php
@include('datafinder::datatable', [
    'custom_datatable' => true,
    'dom_table_id' => 'your_table_id_here',
    'responsive' => true
])
```
###### **_Keys:_**

| Key          | Description                                  | Required |
| -------------------- | -------------------------------------------- | -------- |
| `'custom_datatable'` | Enables custom table logic                   | YES      |
| `'dom_table_id'`     | ID of your custom table                      | YES      |
| `'responsive'`       | Enables DataFinder's responsive enhancements | YES      |


###### **_How to Initialize Custom Usage:_**

When using Custom Usage Mode, your **[DataTables](https://datatables.net/)** initialization must follow a specific format to ensure full compatibility with DataFinder's filtering, layout, and backend integration logic, such as: 

- The custom **[DataTables](https://datatables.net/)** config must be assigned to a variable named `datafinder_custom_config`. This ensures DataFinder can hook into your custom configuration correctly.
> For `ajax` function, following must be kept in consideration:

- `url` must point to your custom backend route.
- `type` must be set to `POST`.
- `data` function must include:
    - `setupFilterObject();`
    - `data.filters = filters;`

Following is the complete example on how to init the custom **[DataTables](https://datatables.net/)**.


```javascript
// After you datatable cdns or plugin file includes
let datafinder_custom_config = {
    processing: true,
    serverSide: true,
    ajax: {
        "url": "YOUR_CUSTOM_ROUTE", // required
        "type": "GET",
        "data": function (data) {
            setupFilterObject(); // required
            data.filters = filters; // required
        },
    },
    columns: [
        { 'title': "YOUR COLUMN TITLE", "data": "YOU DATA KEY", , orderable: false, searchable: false},
        // more
    ],
    responsive: true
};
```