<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\CompetitionRequest as StoreRequest;
use App\Http\Requests\CompetitionRequest as UpdateRequest;

class CompetitionCrudController extends CrudController
{
    public function setup()
    {

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Competition');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/competition');
        $this->crud->setEntityNameStrings('competition', 'competitions');

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
                'name' => 'image',
                'label' => "Name",
                'type' => 'url',
            ],
            [  // Select2
               'label' => "Age Group",
               'type' => 'select2',
               'name' => 'age_group_id', // the db column for the foreign key
               'entity' => 'age_group', // the method that defines the relationship in your Model
               'attribute' => 'description', // foreign key attribute that is shown to user
               'model' => "App\Models\AgeGroup" // foreign key model
            ],
            [  // Select2
               'label' => "Competition Level",
               'type' => 'select2',
               'name' => 'competition_level_id', // the db column for the foreign key
               'entity' => 'competition_level', // the method that defines the relationship in your Model
               'attribute' => 'description', // foreign key attribute that is shown to user
               'model' => "App\Models\CompetitionLevel" // foreign key model
            ],
            [  // Select2
               'label' => "Season",
               'type' => 'select2',
               'name' => 'season_id', // the db column for the foreign key
               'entity' => 'season', // the method that defines the relationship in your Model
               'attribute' => 'description', // foreign key attribute that is shown to user
               'model' => "App\Models\Season" // foreign key model
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
            [  // Select2
               'label' => "Age Group",
               'type' => 'select',
               'name' => 'age_group_id', // the db column for the foreign key
               'entity' => 'age_group', // the method that defines the relationship in your Model
               'attribute' => 'description', // foreign key attribute that is shown to user
               'model' => "App\Models\AgeGroup" // foreign key model
            ],
            [  // Select2
               'label' => "Competition Level",
               'type' => 'select',
               'name' => 'competition_level_id', // the db column for the foreign key
               'entity' => 'competition_level', // the method that defines the relationship in your Model
               'attribute' => 'description', // foreign key attribute that is shown to user
               'model' => "App\Models\CompetitionLevel" // foreign key model
            ],
            [  // Select2
               'label' => "Season",
               'type' => 'select',
               'name' => 'season_id', // the db column for the foreign key
               'entity' => 'season', // the method that defines the relationship in your Model
               'attribute' => 'description', // foreign key attribute that is shown to user
               'model' => "App\Models\Season" // foreign key model
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
        // $this->crud->enableAjaxTable();

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
