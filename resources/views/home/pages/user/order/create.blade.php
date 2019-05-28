@extends('home.layouts.jumbotron')

@section('title')
{{trans('create.title')}}
@endsection


@section('main.title')
  <h1><i class="fa fa-bell-o"></i></h1>
  <h2>{{trans('create.head')}}</h2>
  <h4>{{trans('create.head_1')}}</h4>
  <p>{{trans('create.head_2')}}</p>
  <p>{{trans('create.head_3')}}</p>
@endsection


@section('main.content')
<div class="content-wrapper">
  <form id="orderForm" autocomplete="off" data-toggle="validator">
    <fieldset class="content-wrapper" id="base-info" disabled>
      <h3>{{trans('create.info_1')}}</h3>
      <hr>
    
      <div class="form-group col-sm-4">
        <label class="h4" for="form-name"><i class="fa fa-fw fa-user-circle"></i> {{trans('create.info_2')}}</label>
        <input type="text" class="form-control" id="form-name" value="{{ $user->profile->name or '' }}" disabled>
      </div>
    
      <div class="form-group col-sm-4">
        <label class="h4" for="form-stuid"><i class="fa fa-fw fa-vcard"></i> {{trans('create.info_3')}}</label>
        <input type="text" class="form-control" id="form-stuid" value="{{ $user->profile->stuid or '' }}" disabled>
      </div>
    
      <div class="form-group col-sm-4">
        <label class="h4" for="form-gender"><i class="fa fa-fw fa-transgender"></i> {{trans('create.info_4')}}</label>
        <input type="text" class="form-control" id="form-gender" value="{{ $user->profile->gender or '' }}" disabled>
      </div>
    </fieldset>
    
    <fieldset class="content-wrapper" id="contact-info">
      <h3>{{trans('create.info_5')}}</h3>
      <hr>
    
      <div class="form-group col-md-4">
        <label class="h4" for="form-mobile"><i class="fa fa-fw fa-mobile"></i> {{trans('create.moblie_1')}} <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="form-mobile" name="mobile" value="{{ $user->profile->mobile or '' }}" placeholder="{{trans('create.moblie_2')}}"
                    required pattern="^1[34578]\d{9}$" data-pattern-error="{{trans('create.moblie_3')}}">
        <div class="help-block with-errors"></div>
      </div>
    
      <div class="form-group col-md-4">
        <label class="h4" for="form-qq"><i class="fa fa-fw fa-qq"></i> QQ</label>
        <input type="text" class="form-control" id="form-qq" name="qq" value="{{ $user->profile->qq or '' }}" placeholder="{{trans('create.qq_1')}}"
                    pattern="^[1-9]\d{4,9}$" data-pattern-error="{{trans('create.qq_2')}}">
        <div class="help-block with-errors"><span class="text-muted">{{trans('create.qq_3')}}</span></div>
      </div>
    
      <div class="form-group col-md-4">
        <label class="h4" for="form-wechat"><i class="fa fa-fw fa-wechat"></i> Wechat</label>
        <input type="text" class="form-control" id="form-wechat" name="wechat" value="{{ $user->profile->wechat or '' }}" placeholder="{{trans('create.wechat_1')}}"
                    pattern="^([a-zA-Z][a-zA-Z\d_-]{5,19})|(1[3578]\d{9})|([1-9]\d{4,9})$" data-pattern-error="{{trans('create.wechat_2')}}">
        <div class="help-block with-errors"><span class="text-muted">{{trans('create.qq_3')}}</span></div>
      </div>
    </fieldset>
    
    <fieldset class="content-wrapper" id="repair-info">
      <h3>{{trans('create.details_1')}}</h3>
      <hr>
    
      <div class="form-group col-md-4">
        <label class="h4" for="form-campus"><i class="fa fa-fw fa-map-marker"></i> {{trans('create.camps_1')}} <span class="text-danger">*</span></label>
        <select class="form-control" id="form-campus">           
          @if($user->profile->building !== null)
            @foreach($campuses as $campus)
              @if($campus->id === $user->profile->building->campus->id)
                @if (App::getLocale() == "zh")
                <option value="{{ $campus->id }}" selected>
                  {{ $campus->name }}
                </option>
                @else
                <option value="{{ $campus->id }}" selected>
                  {{ $campus->name_en }}
                </option>
                @endif
              @else
                @if (App::getLocale() == "zh")
                <option value="{{ $campus->id }}">
                  {{ $campus->name }}
                </option>  
                @else
                <option value="{{ $campus->id }}">
                  {{ $campus->name_en }}
                </option>
                @endif
                
              @endif
            @endforeach
          @else
            <option class="hidden" value="" disabled selected>{{trans('create.camps_2')}}</option>
            @foreach($campuses as $campus)
              @if (App::getLocale() == "zh")
              <option value="{{ $campus->id }}">
                {{ $campus->name }}
              </option>
              @else
              <option value="{{ $campus->id }}">
                {{ $campus->name_en }}
              </option>
              @endif
            @endforeach
          @endif
        </select>
        <div class="help-block with-errors"></div>
      </div>
    
      <div class="form-group col-md-4">
        <label class="h4" for="form-building"><i class="fa fa-fw fa-building"></i> {{trans('create.camps_3')}} <span class="text-danger">*</span></label>
        <select class="form-control" id="form-building" name="building" value="{{ $user->profile->dorm_building or '' }}" required>
          <option class="hidden" value="" disabled selected> {{trans('create.camps_4')}} </option>
        </select>
        <div class="help-block with-errors"></div>
      </div>
    
      <div class="form-group col-md-4">
        <label class="h4" for="form-room"><i class="fa fa-fw fa-map-signs"></i> {{trans('create.room_1')}} <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="form-room" name="room" value="{{ $user->profile->dorm_room or '' }}" placeholder="{{trans('create.room_2')}} " required>
        <div class="help-block with-errors"></div>
      </div>
    
      <div class="form-group col-xs-12">
        <label class="h4" for="form-detail"><i class="fa fa-fw fa-stethoscope"></i> {{trans('create.service_1')}}  <span class="text-danger">*</span></label>
        <select class="form-control" id="form-service" name="service" required>
         @foreach($services as $service)
            @if(false === env('XJTUANA_SERVICE_ONSITE_AVALIABLE') && 3 === $service->id)
              <option disabled>{{ $service->name }}（{{ env('XJTUANA_SERVICE_ONSITE_NOTICE', 'Stop Service') }}）</option>
            @else
              @if (App::getLocale() == "zh")
                <option value="{{ $service->id }}">{{ $service->name }}</option>
              @else
                <option value="{{ $service->id }}">{{ $service->name_en }}</option>
              @endif
            @endif
          @endforeach
        </select>
        <div class="help-block with-errors"></div>
      </div>
    
      <div class="form-group col-xs-12">
        <label class="h4" for="form-detail"><i class="fa fa-fw fa-file-text"></i>{{trans('create.details_3')}}<span class="text-danger">*</span></label>
        <textarea class="form-control" id="form-detail" name="detail" rows="5" value="" placeholder="{{trans('create.details_4')}}"
                    required data-required-error="{{trans('create.details_5')}}"></textarea>
        <div class="help-block with-errors"></div>
      </div>
    </fieldset>
  </form>
  
  <div class="content-wrapper">
    <div class="form-group col-xs-12">
      <label class="h4"><i class="fa fa-fw fa-tags"></i> {{trans('create.isMultiple_1')}} <span class="text-danger">*</span></label>
      
      <div class="well well-sm" style="background-color: white">
        <div class="radio">
          <label>
            <input type="radio" name="isMultiple" value="0" required checked>
            {{trans('create.isMultiple_2')}}
          </label>
        </div>
        <div class="radio">
          <label>
            <input type="radio" name="isMultiple" value="1" required>
            {{trans('create.isMultiple_3')}}
          </label>
        </div>
      </div>
    </div>
          
    <div class="form-group col-xs-12">
      <label class="h4"><i class="fa fa-fw fa-tags"></i> {{trans('create.hasErrorNote_1')}} <span class="text-danger">*</span></label>
      
      <div class="well well-sm" style="background-color: white">
        <div class="radio">
          <label>
            <input type="radio" name="hasErrorNote" value="0" required>
            {{trans('create.hasErrorNote_2')}}
          </label>
        </div>
        
        <div class="radio">
          <label>
            <input type="radio" name="hasErrorNote" value="1" required checked>
            {{trans('create.hasErrorNote_3')}}
            <br>
            <span id="codeSpan" class="form-inline">
              {{trans('create.hasErrorNote_4')}}
              <input class="form-control" type="text" name="errnote" required data-required-error="{{trans('create.hasErrorNote_5')}}" />
              <div class="help-block with-errors"></div>
            </span>
          </label>
        </div>
      </div>
    </div>
  </div>
  
  <hr>
  
  <div class="content-wrapper text-left text-muted" id="notice">
    <p>{{trans('create.notice_1')}}</p>
    <p>{{trans('create.notice_2')}}</p>
    <p>{{trans('create.notice_3')}}</p>
    <p>{{trans('create.notice_4')}}</p>
  </div>
  
  <div class="content-wrapper text-center">
    <button class="btn btn-success" id="btn-submit">{{trans('create.submit')}}</button>
  </div>
</div>
@endsection


@section('script')
<script>
    $(function() {
        var campuses = JSON.parse('{!! json_encode($campuses) !!}');
        $('#form-campus').on('change', function() {
            var campus_id = $(this).val();
            
            $.each(campuses, function (index, campus) {
                if (campus.id == campus_id) {
                    $('#form-building').empty();
                    $.each(campus.buildings, function (idx, building) {
                      @if (App::getLocale() == "zh")
                        $('<option>').val(building.id).text(building.name).appendTo('#form-building');
                      @else
                        $('<option>').val(building.id).text(building.name_en).appendTo('#form-building');
                      @endif
                    });
                    $('#form-building').trigger('change');
                }
            });
        })
        .trigger('change');
        $('#form-building').val('{{ $user->profile->building === null ? '' : $user->profile->building->id }}');
        
        var hasErrorNote;
        $('input[name="hasErrorNote"]').on('change', function() {
          (hasErrorNote = Boolean(Number($(this).val()))) ? $('#codeSpan').show() : $('#codeSpan').hide();
        }).trigger('change');
        
        $('#btn-submit').on('click', function () {
            if ($('#orderForm').data('bs.validator').validate().hasErrors()) {
                return false;
            }
            var errNote = $('input[name="errnote"]').val();
            if (hasErrorNote && '' === errNote) {
              swal({
                  title: '{{trans('create.infoShow_1')}}',
                  type: 'info',
                  confirmButtonText: '{{trans('create.confirmButtonText')}}',
              }).then(function() {
                $('input[name="errnote"]').focus();
              });
              return false;
            }
            var isMultiple = Boolean(Number($('input[name="isMultiple"]:checked').val()));
            var dataObj = Object.create(null);
            $.each($('#orderForm').serializeArray(), function(i, field) {
              dataObj[field.name] = field.value;
            });
            dataObj.detail = (isMultiple ? '宿舍多人出现问题' : '宿舍个人出现问题') + '; ' + (hasErrorNote ? '错误代码/提示' + errNote : '没有错误代码/提示') + '; ' + dataObj.detail
            
            swal({
                title: '{{trans('create.infoShow_2')}}',
                type: 'info',
                showCancelButton: true,
                showLoaderOnConfirm: true,
                confirmButtonText: '{{trans('create.confirmButtonText')}}',
                cancelButtonText: '{{trans('create.cancelButtonText')}}',
                preConfirm: function () {
                    return axios.post(
                        '{{ route('api.order.store') }}', 
                        dataObj
                    );
                }
            }).then(function (response) {
                swal({
                    title: response.data.status ? '{{trans('create.error')}}' : '{{trans('create.success')}}',
                    text: response.data.message,
                    type: response.data.status ? 'error' : 'success'
                })
                .then(function () {
                    if (response.data.data.redirect) {
                        window.location = response.data.data.redirect;
                    }
                });
            })
            .catch(swal.noop);
        });


    });
</script>
@endsection