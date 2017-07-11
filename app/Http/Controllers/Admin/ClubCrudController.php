<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ClubRequest as StoreRequest;
use App\Http\Requests\ClubRequest as UpdateRequest;

class ClubCrudController extends CrudController
{
    public function setup()
    {

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Club');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/club');
        $this->crud->setEntityNameStrings('club', 'clubs');

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */

        // $this->crud->setFromDb();

        // ------ CRUD FIELDS
        // $this->crud->addField($options, 'update/create/both');
        $this->crud->addFields([
            [  // Select2
               'label' => "Association",
               'type' => 'select2',
               'name' => 'association_id', // the db column for the foreign key
               'entity' => 'association', // the method that defines the relationship in your Model
               'attribute' => 'name', // foreign key attribute that is shown to user
               'model' => "App\Models\Association" // foreign key model
            ],
            [  // Select2
               'label' => "Category",
               'type' => 'select2',
               'name' => 'category_id', // the db column for the foreign key
               'entity' => 'category', // the method that defines the relationship in your Model
               'attribute' => 'name', // foreign key attribute that is shown to user
               'model' => "App\Models\Category" // foreign key model
            ],
            [ // Text
                'name' => 'fpb_id',
                'label' => "FPB Id",
                'type' => 'number',
                // optionals
                // 'attributes' => ["step" => "any"], // allow decimals
                // 'prefix' => "$",
                // 'suffix' => ".00",
            ],
            [ // Text
                'name' => 'name',
                'label' => "Name",
                'type' => 'text',
                // optional
                //'prefix' => '',
                //'suffix' => ''
            ],
            [ // Text
                'name' => 'alternative_name',
                'label' => "Alternative Name",
                'type' => 'text',
                // optional
                //'prefix' => '',
                //'suffix' => ''
            ],
            [ // Text
                'name' => 'image',
                'label' => "Name",
                'type' => 'url',
            ],
            [ // Text
                'name' => 'founding_date',
                'label' => "Founding Date",
                'type' => 'text',
                // optional
                //'prefix' => '',
                //'suffix' => ''
            ],
            [ // Text
                'name' => 'president',
                'label' => "President",
                'type' => 'text',
                // optional
                //'prefix' => '',
                //'suffix' => ''
            ],
            [ // Text
                'name' => 'address',
                'label' => "Address",
                'type' => 'textarea',
                // optional
                //'prefix' => '',
                //'suffix' => ''
            ],
            [ // Text
                'name' => 'telephone',
                'label' => "Telephone",
                'type' => 'text',
                // optional
                //'prefix' => '',
                //'suffix' => ''
            ],
            [ // Text
                'name' => 'fax_number',
                'label' => "Fax Number",
                'type' => 'text',
                // optional
                //'prefix' => '',
                //'suffix' => ''
            ],
            [ // Text
                'name' => 'email',
                'label' => "Email",
                'type' => 'text',
                // optional
                //'prefix' => '',
                //'suffix' => ''
            ],
            [ // Text
                'name' => 'url',
                'label' => "Url",
                'type' => 'url',
                // optional
                //'prefix' => '',
                //'suffix' => ''
            ],
        ]);
        // $this->crud->removeField('name', 'update/create/both');
        // $this->crud->removeFields($array_of_names, 'update/create/both');

        // ------ CRUD COLUMNS
        // $this->crud->addColumn(); // add a single column, at the end of the stack
        $this->crud->addColumns([
            [  // Select2
               'label' => "Association",
               'type' => 'select',
               'name' => 'association_id', // the db column for the foreign key
               'entity' => 'association', // the method that defines the relationship in your Model
               'attribute' => 'name', // foreign key attribute that is shown to user
               'model' => "App\Models\Association" // foreign key model
            ],
            [  // Select2
               'label' => "Category",
               'type' => 'select',
               'name' => 'category_id', // the db column for the foreign key
               'entity' => 'category', // the method that defines the relationship in your Model
               'attribute' => 'name', // foreign key attribute that is shown to user
               'model' => "App\Models\Category" // foreign key model
            ],
            [ // Text
                'name' => 'fpb_id',
                'label' => "FPB Id",
            ],
            [ // Text
                'name' => 'alternative_name',
                'label' => "Alternative Name",
            ],
            [ // Text
                'name' => 'name',
                'label' => "Name",
            ],
        ]); // add multiple columns, at the end of the stack
        // $this->crud->removeColumn('column_name'); // remove a column from the stack
        // $this->crud->removeColumns(['column_name_1', 'column_name_2']); // remove an array of columns from the stack
        // $this->crud->setColumnDetails('column_name', ['attribute' => 'value']); // adjusts the properties of the
        //  passed in column (by name)
        // $this->crud->setColumnsDetails(['column_1', 'column_2'], ['attribute' => 'value']);

        // ------ CRUD BUTTONS
        // possible positions: 'beginning' and 'end'; defaults to 'beginning' for the 'line' stack, 'end' for the
        //  others;
        // $this->crud->addButton($stack, $name, $type, $content, $position); // add a button; possible types are: view,
        //  model_function
        // $this->crud->addButtonFromModelFunction($stack, $name, $model_function_name, $position); // add a button
        //  whose HTML is returned by a method in the CRUD model
        // $this->crud->addButtonFromView($stack, $name, $view, $position); // add a button whose HTML is in a view
        //  placed at resources\views\vendor\backpack\crud\buttons
        // $this->crud->removeButton($name);
        // $this->crud->removeButtonFromStack($name, $stack);
        // $this->crud->removeAllButtons();
        // $this->crud->removeAllButtonsFromStack('line');

        // ------ CRUD ACCESS
        // $this->crud->allowAccess(['list', 'create', 'update', 'reorder', 'delete']);
        // $this->crud->denyAccess(['list', 'create', 'update', 'reorder', 'delete']);

        // ------ CRUD REORDER
        // $this->crud->enableReorder('label_name', MAX_TREE_LEVEL);
        // NOTE: you also need to do allow access to the right users: $this->crud->allowAccess('reorder');

        // ------ CRUD DETAILS ROW
        // $this->crud->enableDetailsRow();
        // NOTE: you also need to do allow access to the right users: $this->crud->allowAccess('details_row');
        // NOTE: you also need to do overwrite the showDetailsRow($id) method in your EntityCrudController to show
        //  whatever you'd like in the details row OR overwrite the views/backpack/crud/details_row.blade.php

        // ------ REVISIONS
        // You also need to use \Venturecraft\Revisionable\RevisionableTrait;
        // Please check out: https://laravel-backpack.readme.io/docs/crud#revisions
        // $this->crud->allowAccess('revisions');

        // ------ AJAX TABLE VIEW
        // Please note the drawbacks of this though:
        // - 1-n and n-n columns are not searchable
        // - date and datetime columns won't be sortable anymore
        $this->crud->enableAjaxTable();

        // ------ DATATABLE EXPORT BUTTONS
        // Show export to PDF, CSV, XLS and Print buttons on the table view.
        // Does not work well with AJAX datatables.
        // $this->crud->enableExportButtons();

        // ------ ADVANCED QUERIES
        // $this->crud->addClause('active');
        // $this->crud->addClause('type', 'car');
        // $this->crud->addClause('where', 'name', '==', 'car');
        // $this->crud->addClause('whereName', 'car');
        // $this->crud->addClause('whereHas', 'posts', function($query) {
        //     $query->activePosts();
        // });
        // $this->crud->addClause('withoutGlobalScopes');
        // $this->crud->addClause('withoutGlobalScope', VisibleScope::class);
        // $this->crud->with(); // eager load relationships
        // $this->crud->orderBy();
        // $this->crud->groupBy();
        // $this->crud->limit();
    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::storeCrud();
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud();
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }
}
