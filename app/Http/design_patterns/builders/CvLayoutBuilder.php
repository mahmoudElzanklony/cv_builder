<?php

namespace App\Http\design_patterns\builders;

use App\Models\categories;
use App\Models\elements_style;
use App\Models\template_sec_attr_value;
use App\Models\templates;
use App\Models\templates_sections;
use App\Models\users_cvs;
use App\Http\traits\upload_image;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CvLayoutBuilder
{
    use upload_image;
    private $id;
    private $save_type;
    private $data;
    private $template;


    public function __construct($id,$data)
    {
        DB::beginTransaction();
        $this->id = $id;
        $this->data = $data;
    }



    public function save_template()
    {
        if(isset($this->data['name']) && isset($this->data['category_id'])) {
            // we want to create or update template and this is for owner in update owner of tempalte want to update it
            $this->template = templates::query()->updateOrCreate([
                'id' => $this->id ?? null,
                'user_id' => auth()->id()
            ], [
                'name' => $this->data['name'] ?? null,
                'category_id' => $this->data['category_id'] ?? categories::query()->first()->id,
                'visibility' => $this->data['visibility'] ?? 'public',
                'type'=>auth()->user()->role->name == 'client' ? 'u':'s'  // u for user , s for service
            ]);
        }
        return $this;
    }

    public function save_template_sec()
    {
        if(isset($this->template)) {
            // we want to create or update template sections
            foreach($this->data['attr'] as $sec_id => $attributes_values){
                $tem_sec = templates_sections::query()->updateOrCreate([
                    'template_id'=>$this->template->id,
                    'section_id'=>$sec_id
                ],[
                    'template_id'=>$this->template->id,
                    'section_id'=>$sec_id
                ]);
                foreach($attributes_values as $attr_id => $attr){
                    $attr_value = [
                        'template_section_id'=>$tem_sec->id,
                        'attribute_id'=>$attr_id,
                        'answer'=>$attr,
                        'answer_type'=>'text',
                    ];
                    if(is_array($attr)){
                        $attr_value['answer_type'] = 'selection';
                        foreach($attr as $key => $value){
                            $arr_items = collect([json_decode($value,true)])->pluck('value', 'key')->toArray();
                            foreach($arr_items as $key_el => $v) {
                                $attr_value['attribute_id'] = $attr_id;
                                $attr_value['answer'] = $key_el;
                                template_sec_attr_value::query()->updateOrCreate($attr_value, $attr_value);
                            }
                        }
                    }else if(is_string($attr)) {
                        template_sec_attr_value::query()->updateOrCreate($attr_value, $attr_value);
                    }else if($attr instanceof  \Illuminate\Http\UploadedFile){
                        // this is file uploaded
                        try{
                            $img = $this->upload($attr,'cv_personal_images');

                            $attr_value['answer'] = 'cv_personal_images/'.$img;
                            $attr_value['answer_type'] = 'image';
                            template_sec_attr_value::query()->updateOrCreate($attr_value, $attr_value);
                        }catch (\Exception $exception){
                             $attr->store('cv_personal_images');
                        }
                    }
                }
            }
        }

        return $this;
    }

    public function save_template_style()
    {
        if(isset($this->template)) {
            $style_arr = json_decode($this->data['style'],true);
            foreach($style_arr as $style) {
                $section_id = $style['id'];
                try{
                    $tem_sec = templates_sections::query()
                        ->where('template_id','=',$this->template->id)
                        ->where('section_id','=',$section_id)->first();

                }catch (\Exception $e){}
                $section_style = '';

                if (key_exists('header', $style)) {
                    // for main title of section
                    $section_style .= $this->handle_css_txt($style['header']);
                }
                if (key_exists('general', $style)) {
                    // style for general section
                    $section_style .= $this->handle_css_txt($style['general']);
                }
                if($section_style != ''){
                    // save style of section
                    $this->save_style_el($tem_sec->id,'App\Models\templates_sections',$section_style);
                }
                if (key_exists('properties', $style)) {

                    foreach($style['properties'] as $property){
                        $attribute_style = '';
                        $attribute_id = $property['id'];
                        try{
                            $attribute_style .= $this->handle_css_txt($property);
                            $tem_sec_attr_val = template_sec_attr_value::query()
                                ->where('template_section_id','=',$tem_sec->id)
                                ->where('attribute_id','=',$attribute_id)->first();
                            $this->save_style_el($tem_sec_attr_val->id,'App\Models\template_sec_attr_value',$attribute_style);
                        }catch (\Exception $e){}
                    }
                    // for attributes of section

                }

            }
        }
        //dd('ab');
        DB::commit();
    }

    public function handle_css_txt($style)
    {
        $css = '';
        foreach ($style as $k => $v) {
            if($k == 'id') continue;
            $css .= $k.':'.$v.';';
        }
        return $css;
    }

    public function save_style_el($styleable_id,$styleable_type,$style)
    {
        $data = [
            'styleable_id'=>$styleable_id,
            'styleable_type'=>$styleable_type,
            'style'=>$style
        ];
        elements_style::query()->updateOrCreate($data,$data);
    }




}
