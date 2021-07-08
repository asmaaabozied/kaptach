<?php

namespace App\Helpers;


use Form;
use Html;

class BootForm
{

    public static function open($action, array $options = [])
    {
        return Form::open($options) . Form::hidden('action', $action);
    }

    public static function model($model, $action, array $options = [])
    {
        return Form::model($model, $options) . Form::hidden('action', $action);
    }

    public static function close()
    {
        return Form::close();
    }

    public static function input($type, $name, $value = null, $label = null, $errors, $options = [])
    {
        $vName = self::getFieldName($name);
        $options = BootForm::setBootstrapClass($options);
        $html = '<div class="form-group ' . ($errors->has($vName) ? 'has-error' : '') . '">';
        if ($label) {
            $html .= Html::decode(Form::label($name, $label));
        }
        $html .= Form::input($type, $name, $value, $options);
        if ($errors->has($vName)) {
            $html .= '<p class="help-block">' . $errors->first($vName) . '</p>';
        }
        $html .= '</div>';
        return $html;
    }

    public static function hidden($name, $value = null, $options = [])
    {
        return Form::hidden($name, $value, $options);
    }

    public static function textarea($name, $value = null, $label, $errors, $options = [])
    {
        $vName = self::getFieldName($name);
        $options = BootForm::setBootstrapClass($options);
        $html = '<div class="form-group ' . ($errors->has($vName) ? 'has-error' : '') . '">';
        $html .= '<label for="' . $name . '">' . $label . '</label>';
        $html .= Form::textarea($name, $value, $options);
        if ($errors->has($vName)) {
            $html .= '<p class="help-block">' . $errors->first($vName) . '</p>';
        }
        $html .= '</div>';
        return $html;
    }

    public static function select($name, $label, $list = [], $selected = null, $errors, $options = [])
    {
        $vName = self::getFieldName($name);
        $options = BootForm::setBootstrapClass($options);
        $html = '<div class="form-group ' . ($errors->has($vName) ? 'has-error' : '') . '">';
        $html .= '<label for="' . $name . '">' . $label . '</label>';
        $html .= Form::select($name, $list, $selected, $options);
        if ($errors->has($vName)) {
            $html .= '<p class="help-block">' . $errors->first($vName) . '</p>';
        }
        $html .= '</div>';
        return $html;
    }

    public static function checkbox($name, $label, $value = 1, $checked = null, $withHidden = true)
    {
        $html = '<div class="checkbox">';
        if ($withHidden) {
            $html .= '<input type="hidden" name="' . $name . '" value="0" />';
        }
        $html .= '<label>';
        $html .= '<input name="' . $name . '" type="checkbox" class="chkBlock" value="' . $value . '" ' . ($checked ? 'checked' : '') . '>';
//        $html .= '<span class="cr"><i class="cr-icon glyphicon glyphicon-ok"></i></span>';
        $html .= $label;
        $html .= '</label>';
        $html .= '</div>';
        return $html;
    }

    public static function radio($name, $value = null, $checked = null, $options = [])
    {
        return Form::radio($name, $value, $checked, $options = []);
    }

    public static function image($url, $name = null, $label = 'Image', $errors)
    {
        $vName = self::getFieldName($name);
        $html = '<div class="form-group ' . ($errors->has($vName) ? 'has-error' : '') . '">';
        $html .= '<label for="' . $name . '">' . $label . '</label>';
        $html .= '<div class="input-group image-preview">';
        $html .= '<input type="text" class="form-control image-preview-filename" data-url="' . $url . '" disabled="disabled" value=" ' . @end(explode('/', $url)) . ' ">';
        $html .= '<span class="input-group-btn">';
        $html .= '<button type="button" class="btn btn-default image-preview-clear" style="display:none;">';
        $html .= '<span class="glyphicon glyphicon-remove"></span> Clear';
        $html .= '</button>';
        $html .= '<div class="btn btn-default image-preview-input">';
        $html .= '<span class="glyphicon glyphicon-folder-open"></span>';
        $html .= '<span class="image-preview-input-title"> Browse</span>';

        $html .= '<input type="file" accept="image/png, image/jpeg, image/gif" name="' . $name . '"/>';
        $html .= '</div>';
        $html .= '</span>';
        $html .= '</div>';
        $html .= '</div>';
        if ($errors->has($vName)) {
            $html .= '<p class="help-block">' . $errors->first($vName) . '</p>';
        }
        $html .= '</div>';
        return $html;
    }

    public static function file($name, $label, $errors, $options = [])
    {
        $html = '<div class="form-group ' . ($errors->has($name) ? 'has-error' : '') . '">';
        $html .= '<label for="' . $name . '">' . $label . '</label>';
        $html .= Form::file($name, $options);
        if ($errors->has($name)) {
            $html .= '<p class="help-block">' . $errors->first($name) . '</p>';
        }
        $html .= '</div>';
        return $html;
    }

    public static function setBootstrapClass($options)
    {
        if (empty($options['class'])) {
            $options['class'] = 'form-control';
        } else {
            $options['class'] .= ' form-control';
        }
        return $options;
    }

    public static function submit($label = 'Save', $icon = 'fa-floppy-o')
    {
        $label = __('buttons.save');
        return '<button type="submit" class="btn btn-primary"> <i class="fa ' . $icon . '" aria-hidden="true"></i> ' . $label . '</button>';
    }

    public static function link($url, $label = '', $type, $icon = '', $align = '')
    {
        return '<a href="' . $url . '" class="btn btn-' . $type . ' ' . ($align ? 'pull-' . $align : '') . '"><i class="fa ' . $icon . '" aria-hidden="true"></i> ' . $label . '</a>';
    }

    public static function routeLink($route, $parameters = [], $attributes = [], $visible = true)
    {
        if ($visible) {
            $html = '<a href="' . route($route, $parameters) . '"';
            $class = '';
            if (!empty($attributes['class'])) {
                $class = $attributes['class'];
            }
            if (!empty($attributes['type'])) {
                $class .= ' btn btn-' . $attributes['type'];
            }
            if (!empty($attributes['align'])) {
                $class .= ' pull-' . $attributes['align'];
            }
            if (!empty($attributes['id'])) {
                $html .= ' id="' . $attributes['id'] . '"';
            }
            if (!empty($attributes['title'])) {
                $html .= ' title="' . $attributes['title'] . '"';
            }
            if (!empty($attributes['title'])) {
                $html .= ' title="' . $attributes['title'] . '"';
            }
            if (!empty($attributes['data-target'])) {
                $html .= ' data-target="'.$attributes['data-target'].'"';
            }
            if (!empty($attributes['target'])) {
                $html .= ' target="_blank"';
            }
            if (!empty($attributes['style'])) {
                $html .= ' style="' . $attributes['style'] . '"';
            }
            if ($class) {
                $html .= ' class="' . $class . '"';
            }
            $html .= '>';
            if (!empty($attributes['value'])) {
                $html .= $attributes['value'];
            }
            if (!empty($attributes['icon'])) {
                $html .= '<i class="fa ' . $attributes['icon'] . '" aria-hidden="true"></i> ';
            }
            if (!empty($attributes['label'])) {
                $html .= $attributes['label'];
            }
            $html .= '</a>';
            return $html;
        }

    }

    public static function linkOfShow($route, $parameters, $name, $visible = true)
    {
        if ($visible) {
            return BootForm::routeLink($route, $parameters, ['title' => 'Show data of #' . $name, 'type' => 'link', 'tooltip' => true, 'icon' => 'fa-eye']);
        }
        return '';
    }

    public static function linkOfEdit($route, $parameters, $name, $visible = true)
    {
        if ($visible) {
            return BootForm::routeLink($route, $parameters, ['title' => 'Edit data of #' . $name, 'type' => 'link', 'tooltip' => true, 'icon' => 'fa-pencil']);
        }
        return '';
    }

    public static function linkOfRestore($route, $parameters, $name, $visible = true)
    {
        if ($visible) {
            return BootForm::routeLink($route, $parameters, ['title' => 'Restore data of #' . $name, 'type' => 'link', 'tooltip' => true, 'icon' => 'fa-undo']);
        }
        return '';
    }

    public static function linkOfDelete($route, $parameters, $name, $type = 'link', $tooltip = true, $label = '', $visible = true)
    {
        if ($visible) {
            $html = Form::open(['method' => 'DELETE', 'url' => route($route, $parameters), 'style' => 'display:inline;' . ($label ? 'margin-left: 8px;' : '')]);
            $html .= Form::button(($label ? $label : '<i class="fa fa-trash ' . ($type === 'link' ? '' : '') . '"></i>'), [
                'type' => 'submit',
                'title' => 'Delete data of #' . $name,
                'class' => 'btn btn-' . $type . ' confirmed sub-menu',
                'data-confirm-message' => 'Are you sure to delete the data of #' . $name . '?',
                'data-toggle' => $tooltip ? 'tooltip' : ''
            ]);
            $html .= Form::close();
            return $html;
        }
        return '';
    }

    public static function linkOfPermanentDelete($route, $parameters, $name, $type = 'link', $tooltip = true, $label = '', $visible = true)
    {
        if ($visible) {
            $html = Form::open(['method' => 'DELETE', 'url' => route($route, $parameters), 'style' => 'display:inline']);
            $html .= Form::button('<i class="fa fa-trash ' . ($type === 'link' ? '' : '') . '"></i>' . ($label ? ' ' . $label : ''), [
                'type' => 'submit',
                'title' => 'Permanent delete data of #' . $name,
                'class' => 'btn btn-' . $type . ' confirmed',
                'data-confirm-message' => 'Are you sure to delete the data of #' . $name . '? this action cnnot be undone.',
                'data-toggle' => $tooltip ? 'tooltip' : ''
            ]);
            $html .= Form::close();
            return $html;
        }
        return '';
    }

    public static function getFieldName($name)
    {
        $name = str_replace('[]', '', $name);
        $name = str_replace('[', '.', $name);
        return str_replace(']', '', $name);
    }

    public static function tree($name, $items, $values = [], $displayField = 'name', $valueField = 'id')
    {
        $html = '<ul>';
        foreach ($items as $item) {
            $html .= '<li class="node-' . $item->id . '"><label><input name="' . $name . '[]" value="' . $item->id . '" type="checkbox" ' . (in_array($item->id, $values) ? 'checked' : '') . '> <span>' . $item->{$displayField} . '</span></label>';
            if (!empty($item->children)) {
                $html .= self::tree($name, $item->children, $values);
            }
        }
        $html .= '</ul>';
        return $html;
    }


    public static function orderLink($route, $label, $orderby)
    {
        $orderType = request()->input('ordertype', 'desc');
        if ($orderType == 'desc') {
            $orderType = 'asc';
            $icon = '<i class="fa fa-sort-desc" aria-hidden="true"></i>';
        } else {
            $orderType = 'desc';
            $icon = '<i class="fa fa-sort-asc" aria-hidden="true"></i>';
        }

        $params = request()->input();
        $params = array_merge($params, ['orderby' => $orderby, 'ordertype' => $orderType]);


        return '<a href="' . route($route, $params) . '">' . $label . ' ' . $icon . '</a>';
    }
}
