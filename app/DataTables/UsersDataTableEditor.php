<?php

namespace App\DataTables;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTablesEditor;

class UsersDataTableEditor extends DataTablesEditor
{
    protected $model = User::class;

    /**
     * Get create action validation rules.
     */
    public function createRules(): array
    {
        return [
            'email' => 'required|email|max:255|unique:'.$this->resolveModel()->getTable(),
            'name' => 'required|max:255',
            'password' => 'required||max:255|confirmed',
        ];
    }

    /**
     * Get edit action validation rules.
     */
    public function editRules(Model $model): array
    {
        return [
            'email' => 'sometimes|required|max:255|email|'.Rule::unique($model->getTable())->ignore($model->getKey()),
            'name' => 'sometimes|required|max:255',
            'password' => 'sometimes|required|max:255',
        ];
    }

    /**
     * Get remove action validation rules.
     */
    public function removeRules(Model $model): array
    {
        return [];
    }

    /**
     * Event hook that is fired after `creating` and `updating` hooks, but before
     * the model is saved to the database.
     */
    public function saving(Model $model, array $data): array
    {
        // Before saving the model, hash the password.
        if (! empty(data_get($data, 'password'))) {
            data_set($data, 'password', bcrypt($data['password']));
        }

        return $data;
    }

    /**
     * Event hook that is fired after `created` and `updated` events.
     */
    public function saved(Model $model, array $data): Model
    {
        // do something after saving the model

        return $model;
    }
    /**
     * Event hook that is fired before creating a new record.
     *
     * @param \Illuminate\Database\Eloquent\Model $model Empty model instance.
     * @param array $data Attribute values array received from Editor.
     * @return array The updated attribute values array.
     */
    public function creating(Model $model, array $data)
    {
        // Code can change the attribute values array before saving data to the
        // database.
        // Can be used to initialize values on new model.

        // Since arrays are copied when passed by value, the function must return
        // the updated $data array
        $data['password'] = bcrypt($data['password']);
        return $data;
    }

    /**
     * Event hook that is fired after a new record is created.
     *
     * @param \Illuminate\Database\Eloquent\Model $model The newly created model.
     * @param array $data Attribute values array received from `creating` or
     *   `saving` hook.
     * @return \Illuminate\Database\Eloquent\Model Since version 1.8.0 it must
     *   return the $model.
     */
    public function created(Model $model, array $data)
    {
        // Can be used to mutate state of newly created model that is returned to
        // Editor.

        // Prior to version 1.8.0 of Laravel DataTables Editor the hook was not
        // required to return the $model.
        // In version 1.8.0+ the hook must return the $model instance:
        return $model;
    }
    /**
     * Event hook that is fired before updating an existing record.
     *
     * @param \Illuminate\Database\Eloquent\Model $model Model instance retrived
     *  retrived from database.
     * @param array $data Attribute values array received from Editor.
     * @return array The updated attribute values array.
     */
    public function updating(Model $model, array $data) {
        // Can be used to modify the attribute values received from Editor before
        // applying changes to model.

        // Since arrays are copied when passed by value, the function must return
        // the updated $data array
        return $data;
    }

    /**
     * Event hook that is fired after the record was updated.
     *
     * @param \Illuminate\Database\Eloquent\Model $model Updated model instance.
     * @param array $data Attribute values array received from `updating` or
     *   `saving` hook.
     * @return \Illuminate\Database\Eloquent\Model Since version 1.8.0 it must
     *   return the $model.
     */
    public function updated(Model $model, array $data) {
        // Can be used to mutate state of updated model that is returned to Editor.

        // Prior to version 1.8.0 of Laravel DataTables Editor the hook was not required
        // to return the $model.
        // In version 1.8.0+ the hook must return the $model instance:
        return $model;
    }
    /**
     * Event hook that is fired before deleting an existing record.
     *
     * @param \Illuminate\Database\Eloquent\Model $model The original model
     *   retrieved from database.
     * @param array $data Attribute values array received from Editor.
     * @return void
     */
    public function deleting(Model $model, array $data) {
        // Record still exists in database. Code can be used to delete records from
        // child tables that don't specify cascade deletes on the foreign key
        // definition.
    }

    /**
     * Event hook that is fired after deleting the record from database.
     *
     * @param \Illuminate\Database\Eloquent\Model $model The original model
     *   retrieved from database.
     * @param array $data Attribute values array received from Editor.
     * @return void
     */
    public function deleted(Model $model, array $data) {
        // Record no longer exists in database, but $model instance still contains
        // data as it was before deleting. Any changes to the $model instance will
        // be returned to Editor.
    }
}
