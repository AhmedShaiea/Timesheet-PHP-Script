@extends('layouts.master')

@section('title', trans('messages.User'))

@section('sidebar')
    @parent

    <p>{{ trans('messages.Userrules') }}</p>
@endsection

@section('content')
  <h3>{{ trans('messages.Edit') }} {{ trans('messages.User') }}:</h3>

  <form id="" action="" method="POST" enctype="multipart/form-data" >
      <div class="container-fluid">
          <div class="row">
            <div  class="col-xs-12 col-md-6">{{ trans('messages.Username') }}*: <input type="text" name="username" id="username" class="form-control" value="{{  $user->username }}" disabled /></div>
          </div>

          <div class="row">
            <div  class="col-xs-12 col-md-6">{{ trans('messages.Password') }}*: <input type="password" name="password" id="password" class="form-control" value="{{  $user->password }}" disabled /></div>
          </div>

          <div class="row">
            <div  class="col-xs-12 col-md-6"><span class="bold">{{ trans('messages.ConfirmPassword') }}*: </span><input type="password" name="password_confirmation" id="password_confirmation" class="form-control" value="{{  $user->password }}" disabled /></div>
          </div>

          <div class="row">
            <div  class="col-xs-12 col-md-6">{{ trans('messages.FirstName') }}*: <input type="text" name="first_name" id="first_name" class="form-control" value="{{  $user->first_name }}" /></div>
          </div>

          <div class="row">
            <div  class="col-xs-12 col-md-6">{{ trans('messages.LastName') }}*: <input type="text" name="last_name" id="last_name" class="form-control" value="{{  $user->last_name }}" /></div>
          </div>

          <div class="row">
            <div  class="col-xs-12 col-md-6">{{ trans('messages.Email') }}*: <input type="text" name="email" id="email" class="form-control" value="{{  $user->email }}" /></div>
          </div>

          <div class="row">
            <div  class="col-xs-12 col-md-6"><span class="bold">{{ trans('messages.Division') }}*: </span>
             <select class="form-control" name="division" id="division">
                 <option value="0" {{ empty($user->division) ? 'selected' : '' }}>{{ trans('messages.Chooseone') }}...</option>
             @foreach ($divisions as $division)
                 <option value="{{ $division->id }}" {{ intval($user->division) === intval($division->id) ? 'selected' : '' }}>{{ $division->name }}</option>
             @endforeach
            </select> 
            </div>
          </div>

          <div class="row">
            <div  class="col-xs-12 col-md-6"><span class="bold">{{ trans('messages.Role') }}*: </span>
             <select class="form-control" name="role" id="role">
                 <option value="0" {{ empty($user->role) ? 'selected' : '' }}>{{ trans('messages.Chooseone') }}...</option>
             @foreach ($roles as $role)
                 <option value="{{ $role->id }}" {{ intval($user->role) === intval($role->id) ? 'selected' : '' }}>{{ $role->name }}</option>
             @endforeach
            </select> 
            </div>
          </div>
		  
		  <div class="row">
            <div  class="col-xs-12 col-md-6"><span class="bold">{{ trans('messages.Reportto') }}*: </span>
             <select class="form-control" name="reportto" id="reportto">
                 <option value="0" selected>{{ trans('messages.Chooseone') }}...</option>
             @foreach ($users as $myuser)
			 <option value="{{ $myuser->id }}" {{ intval($user->reportto) === intval($myuser->id) ? 'selected' : '' }}>{{ ucfirst($myuser->first_name) . ' ' . ucfirst($myuser->last_name) }}</option>
             @endforeach
            </select> 
            </div>
          </div>

          <div class="row">
            <div  class="col-xs-12 col-md-6">{{ trans('messages.Status') }}:
                <select class="form-control" name="status" id="status">
                    <option value="0" {{ empty($user->status) ? 'selected' : ''  }} >{{ trans('messages.NotActive') }}</option>
                    <option value="1" {{ empty($user->status) ? '' : 'selected'  }} >{{ trans('messages.Active') }}</option>
                </select> 
            </div>
          </div>

          <div class="row">
            <div  class="col-xs-12 col-md-6">{{ trans('messages.Phone') }}: <input type="text" name="phone" id="phone" class="form-control" value="{{  $user->phone }}" /></div>
          </div>

          <div class="row">
            <div  class="col-xs-12 col-md-6">{{ trans('messages.Address') }}: <input type="text" name="address" id="address" class="form-control" value="{{  $user->address }}" /></div>
          </div>

          <div class="row">
            <div  class="col-xs-12 col-md-6">{{ trans('messages.Address2') }}: <input type="text" name="address2" id="address2" class="form-control" value="{{  $user->address2 }}" /></div>
          </div>

          <div class="row">
            <div  class="col-xs-12 col-md-6">{{ trans('messages.City') }}: <input type="text" name="city" id="city" class="form-control" value="{{  $user->city }}" /></div>
          </div>

          <div class="row">
            <div  class="col-xs-12 col-md-6">{{ trans('messages.Province') }}: <input type="text" name="province" id="province" class="form-control" value="{{  $user->province }}" /></div>
          </div>

          <div class="row">
            <div  class="col-xs-12 col-md-6">{{ trans('messages.Country') }}:
				<select class="form-control" name="country" id="country">
                    <option value="0" {{ empty($user->country) ? 'selected' : '' }}>{{ trans('messages.Chooseone') }}...</option>
			        <option value="Afghanistan" {{ $user->country === 'Afghanistan' ? 'selected' : '' }}>Afghanistan</option>
					<option value="Albania" {{ $user->country === 'Albania' ? 'selected' : '' }}>Albania</option>
					<option value="Algeria" {{ $user->country === 'Algeria' ? 'selected' : '' }}>Algeria</option>
					<option value="American Samoa" {{ $user->country === 'American Samoa' ? 'selected' : '' }}>American Samoa</option>
					<option value="Andorra" {{ $user->country === 'Andorra' ? 'selected' : '' }}>Andorra</option>
					<option value="Angola" {{ $user->country === 'Angola' ? 'selected' : '' }}>Angola</option>
					<option value="Anguilla" {{ $user->country === 'Anguilla' ? 'selected' : '' }}>Anguilla</option>
					<option value="Antartica" {{ $user->country === 'Antartica' ? 'selected' : '' }}>Antarctica</option>
					<option value="Antigua and Barbuda" {{ $user->country === 'Antigua and Barbuda' ? 'selected' : '' }}>Antigua and Barbuda</option>
					<option value="Argentina" {{ $user->country === 'Argentina' ? 'selected' : '' }}>Argentina</option>
					<option value="Armenia" {{ $user->country === 'Armenia' ? 'selected' : '' }}>Armenia</option>
					<option value="Aruba" {{ $user->country === 'Aruba' ? 'selected' : '' }}>Aruba</option>
					<option value="Australia" {{ $user->country === 'Australia' ? 'selected' : '' }}>Australia</option>
					<option value="Austria" {{ $user->country === 'Austria' ? 'selected' : '' }}>Austria</option>
					<option value="Azerbaijan" {{ $user->country === 'Azerbaijan' ? 'selected' : '' }}>Azerbaijan</option>
					<option value="Bahamas" {{ $user->country === 'Bahamas' ? 'selected' : '' }}>Bahamas</option>
					<option value="Bahrain" {{ $user->country === 'Bahrain' ? 'selected' : '' }}>Bahrain</option>
					<option value="Bangladesh" {{ $user->country === 'Bangladesh' ? 'selected' : '' }}>Bangladesh</option>
					<option value="Barbados" {{ $user->country === 'Barbados' ? 'selected' : '' }}>Barbados</option>
					<option value="Belarus" {{ $user->country === 'Belarus' ? 'selected' : '' }}>Belarus</option>
					<option value="Belgium" {{ $user->country === 'Belgium' ? 'selected' : '' }}>Belgium</option>
					<option value="Belize" {{ $user->country === 'Belize' ? 'selected' : '' }}>Belize</option>
					<option value="Benin" {{ $user->country === 'Benin' ? 'selected' : '' }}>Benin</option>
					<option value="Bermuda" {{ $user->country === 'Bermuda' ? 'selected' : '' }}>Bermuda</option>
					<option value="Bhutan" {{ $user->country === 'Bhutan' ? 'selected' : '' }}>Bhutan</option>
					<option value="Bolivia" {{ $user->country === 'Bolivia' ? 'selected' : '' }}>Bolivia</option>
					<option value="Bosnia and Herzegowina" {{ $user->country === 'Bosnia and Herzegowina' ? 'selected' : '' }}>Bosnia and Herzegowina</option>
					<option value="Botswana" {{ $user->country === 'Botswana' ? 'selected' : '' }}>Botswana</option>
					<option value="Bouvet Island" {{ $user->country === 'Bouvet Island' ? 'selected' : '' }}>Bouvet Island</option>
					<option value="Brazil" {{ $user->country === 'Brazil' ? 'selected' : '' }}>Brazil</option>
					<option value="British Indian Ocean Territory" {{ $user->country === 'British Indian Ocean Territory' ? 'selected' : '' }}>British Indian Ocean Territory</option>
					<option value="Brunei Darussalam" {{ $user->country === 'Brunei Darussalam' ? 'selected' : '' }}>Brunei Darussalam</option>
					<option value="Bulgaria" {{ $user->country === 'Bulgaria' ? 'selected' : '' }}>Bulgaria</option>
					<option value="Burkina Faso" {{ $user->country === 'Burkina Faso' ? 'selected' : '' }}>Burkina Faso</option>
					<option value="Burundi" {{ $user->country === 'Burundi' ? 'selected' : '' }}>Burundi</option>
					<option value="Cambodia" {{ $user->country === 'Cambodia' ? 'selected' : '' }}>Cambodia</option>
					<option value="Cameroon" {{ $user->country === 'Cameroon' ? 'selected' : '' }}>Cameroon</option>
					<option value="Canada" {{ $user->country === 'Canada' ? 'selected' : '' }}>Canada</option>
					<option value="Cape Verde" {{ $user->country === 'Cape Verde' ? 'selected' : '' }}>Cape Verde</option>
					<option value="Cayman Islands" {{ $user->country === 'Cayman Islands' ? 'selected' : '' }}>Cayman Islands</option>
					<option value="Central African Republic" {{ $user->country === 'Central African Republic' ? 'selected' : '' }}>Central African Republic</option>
					<option value="Chad" {{ $user->country === 'Chad' ? 'selected' : '' }}>Chad</option>
					<option value="Chile" {{ $user->country === 'Chile' ? 'selected' : '' }}>Chile</option>
					<option value="China" {{ $user->country === 'China' ? 'selected' : '' }}>China</option>
					<option value="Christmas Island" {{ $user->country === 'Christmas Island' ? 'selected' : '' }}>Christmas Island</option>
					<option value="Cocos Islands" {{ $user->country === 'Cocos Islands' ? 'selected' : '' }}>Cocos (Keeling) Islands</option>
					<option value="Colombia" {{ $user->country === 'Colombia' ? 'selected' : '' }}>Colombia</option>
					<option value="Comoros" {{ $user->country === 'Comoros' ? 'selected' : '' }}>Comoros</option>
					<option value="Congo" {{ $user->country === 'Congo' ? 'selected' : '' }}>Congo</option>
					<option value="Congo" {{ $user->country === 'Congo' ? 'selected' : '' }}>Congo, the Democratic Republic of the</option>
					<option value="Cook Islands" {{ $user->country === 'Cook Islands' ? 'selected' : '' }}>Cook Islands</option>
					<option value="Costa Rica" {{ $user->country === 'Costa Rica' ? 'selected' : '' }}>Costa Rica</option>
					<option value="Cota D'Ivoire" {{ $user->country === "Cota D'Ivoire" ? 'selected' : '' }}>Cote d'Ivoire</option>
					<option value="Croatia" {{ $user->country === 'Croatia' ? 'selected' : '' }}>Croatia (Hrvatska)</option>
					<option value="Cuba" {{ $user->country === 'Cuba' ? 'selected' : '' }}>Cuba</option>
					<option value="Cyprus" {{ $user->country === 'Cyprus' ? 'selected' : '' }}>Cyprus</option>
					<option value="Czech Republic" {{ $user->country === 'Czech Republic' ? 'selected' : '' }}>Czech Republic</option>
					<option value="Denmark" {{ $user->country === 'Denmark' ? 'selected' : '' }}>Denmark</option>
					<option value="Djibouti" {{ $user->country === 'Djibouti' ? 'selected' : '' }}>Djibouti</option>
					<option value="Dominica" {{ $user->country === 'Dominica' ? 'selected' : '' }}>Dominica</option>
					<option value="Dominican Republic" {{ $user->country === 'Dominican Republic' ? 'selected' : '' }}>Dominican Republic</option>
					<option value="East Timor" {{ $user->country === 'East Timor' ? 'selected' : '' }}>East Timor</option>
					<option value="Ecuador" {{ $user->country === 'Ecuador' ? 'selected' : '' }}>Ecuador</option>
					<option value="Egypt" {{ $user->country === 'Egypt' ? 'selected' : '' }}>Egypt</option>
					<option value="El Salvador" {{ $user->country === 'El Salvador' ? 'selected' : '' }}>El Salvador</option>
					<option value="Equatorial Guinea" {{ $user->country === 'Equatorial Guinea' ? 'selected' : '' }}>Equatorial Guinea</option>
					<option value="Eritrea" {{ $user->country === 'Eritrea' ? 'selected' : '' }}>Eritrea</option>
					<option value="Estonia" {{ $user->country === 'Estonia' ? 'selected' : '' }}>Estonia</option>
					<option value="Ethiopia" {{ $user->country === 'Ethiopia' ? 'selected' : '' }}>Ethiopia</option>
					<option value="Falkland Islands" {{ $user->country === 'Falkland Islands' ? 'selected' : '' }}>Falkland Islands (Malvinas)</option>
					<option value="Faroe Islands" {{ $user->country === 'Faroe Islands' ? 'selected' : '' }}>Faroe Islands</option>
					<option value="Fiji" {{ $user->country === 'Fiji' ? 'selected' : '' }}>Fiji</option>
					<option value="Finland" {{ $user->country === 'Finland' ? 'selected' : '' }}>Finland</option>
					<option value="France" {{ $user->country === 'France' ? 'selected' : '' }}>France</option>
					<option value="France Metropolitan" {{ $user->country === 'France Metropolitan' ? 'selected' : '' }}>France, Metropolitan</option>
					<option value="French Guiana" {{ $user->country === 'French Guiana' ? 'selected' : '' }}>French Guiana</option>
					<option value="French Polynesia" {{ $user->country === 'French Polynesia' ? 'selected' : '' }}>French Polynesia</option>
					<option value="French Southern Territories" {{ $user->country === 'French Southern Territories' ? 'selected' : '' }}>French Southern Territories</option>
					<option value="Gabon" {{ $user->country === 'Gabon' ? 'selected' : '' }}>Gabon</option>
					<option value="Gambia" {{ $user->country === 'Gambia' ? 'selected' : '' }}>Gambia</option>
					<option value="Georgia" {{ $user->country === 'Georgia' ? 'selected' : '' }}>Georgia</option>
					<option value="Germany" {{ $user->country === 'Germany' ? 'selected' : '' }}>Germany</option>
					<option value="Ghana" {{ $user->country === 'Ghana' ? 'selected' : '' }}>Ghana</option>
					<option value="Gibraltar" {{ $user->country === 'Gibraltar' ? 'selected' : '' }}>Gibraltar</option>
					<option value="Greece" {{ $user->country === 'Greece' ? 'selected' : '' }}>Greece</option>
					<option value="Greenland" {{ $user->country === 'Greenland' ? 'selected' : '' }}>Greenland</option>
					<option value="Grenada" {{ $user->country === 'Grenada' ? 'selected' : '' }}>Grenada</option>
					<option value="Guadeloupe" {{ $user->country === 'Guadeloupe' ? 'selected' : '' }}>Guadeloupe</option>
					<option value="Guam" {{ $user->country === 'Guam' ? 'selected' : '' }}>Guam</option>
					<option value="Guatemala" {{ $user->country === 'Guatemala' ? 'selected' : '' }}>Guatemala</option>
					<option value="Guinea" {{ $user->country === 'Guinea' ? 'selected' : '' }}>Guinea</option>
					<option value="Guinea-Bissau" {{ $user->country === 'Guinea-Bissau' ? 'selected' : '' }}>Guinea-Bissau</option>
					<option value="Guyana" {{ $user->country === 'Guyana' ? 'selected' : '' }}>Guyana</option>
					<option value="Haiti" {{ $user->country === 'Haiti' ? 'selected' : '' }}>Haiti</option>
					<option value="Heard and McDonald Islands" {{ $user->country === 'Heard and McDonald Islands' ? 'selected' : '' }}>Heard and Mc Donald Islands</option>
					<option value="Holy See" {{ $user->country === 'Holy See' ? 'selected' : '' }}>Holy See (Vatican City State)</option>
					<option value="Honduras" {{ $user->country === 'Honduras' ? 'selected' : '' }}>Honduras</option>
					<option value="Hong Kong" {{ $user->country === 'Hong Kong' ? 'selected' : '' }}>Hong Kong</option>
					<option value="Hungary" {{ $user->country === 'Hungary' ? 'selected' : '' }}>Hungary</option>
					<option value="Iceland" {{ $user->country === 'Iceland' ? 'selected' : '' }}>Iceland</option>
					<option value="India" {{ $user->country === 'India' ? 'selected' : '' }}>India</option>
					<option value="Indonesia" {{ $user->country === 'Indonesia' ? 'selected' : '' }}>Indonesia</option>
					<option value="Iran" {{ $user->country === 'Iran' ? 'selected' : '' }}>Iran (Islamic Republic of)</option>
					<option value="Iraq" {{ $user->country === 'Iraq' ? 'selected' : '' }}>Iraq</option>
					<option value="Ireland" {{ $user->country === 'Ireland' ? 'selected' : '' }}>Ireland</option>
					<option value="Israel" {{ $user->country === 'Israel' ? 'selected' : '' }}>Israel</option>
					<option value="Italy" {{ $user->country === 'Italy' ? 'selected' : '' }}>Italy</option>
					<option value="Jamaica" {{ $user->country === 'Jamaica' ? 'selected' : '' }}>Jamaica</option>
					<option value="Japan" {{ $user->country === 'Japan' ? 'selected' : '' }}>Japan</option>
					<option value="Jordan" {{ $user->country === 'Jordan' ? 'selected' : '' }}>Jordan</option>
					<option value="Kazakhstan" {{ $user->country === 'Kazakhstan' ? 'selected' : '' }}>Kazakhstan</option>
					<option value="Kenya" {{ $user->country === 'Kenya' ? 'selected' : '' }}>Kenya</option>
					<option value="Kiribati" {{ $user->country === 'Kiribati' ? 'selected' : '' }}>Kiribati</option>
					<option value="Democratic People's Republic of Korea" {{ $user->country === "Democratic People's Republic of Korea" ? 'selected' : '' }}>Korea, Democratic People's Republic of</option>
					<option value="Korea" {{ $user->country === 'Korea' ? 'selected' : '' }}>Korea, Republic of</option>
					<option value="Kuwait" {{ $user->country === 'Kuwait' ? 'selected' : '' }}>Kuwait</option>
					<option value="Kyrgyzstan" {{ $user->country === 'Kyrgyzstan' ? 'selected' : '' }}>Kyrgyzstan</option>
					<option value="Lao" {{ $user->country === 'Lao' ? 'selected' : '' }}>Lao People's Democratic Republic</option>
					<option value="Latvia" {{ $user->country === 'Latvia' ? 'selected' : '' }}>Latvia</option>
					<option value="Lebanon" {{ $user->country === 'Lebanon' ? 'selected' : '' }}>Lebanon</option>
					<option value="Lesotho" {{ $user->country === 'Lesotho' ? 'selected' : '' }}>Lesotho</option>
					<option value="Liberia" {{ $user->country === 'Liberia' ? 'selected' : '' }}>Liberia</option>
					<option value="Libyan Arab Jamahiriya" {{ $user->country === 'Libyan Arab Jamahiriya' ? 'selected' : '' }}>Libyan Arab Jamahiriya</option>
					<option value="Liechtenstein" {{ $user->country === 'Liechtenstein' ? 'selected' : '' }}>Liechtenstein</option>
					<option value="Lithuania" {{ $user->country === 'Lithuania' ? 'selected' : '' }}>Lithuania</option>
					<option value="Luxembourg" {{ $user->country === 'Luxembourg' ? 'selected' : '' }}>Luxembourg</option>
					<option value="Macau" {{ $user->country === 'Macau' ? 'selected' : '' }}>Macau</option>
					<option value="Macedonia" {{ $user->country === 'Macedonia' ? 'selected' : '' }}>Macedonia, The Former Yugoslav Republic of</option>
					<option value="Madagascar" {{ $user->country === 'Madagascar' ? 'selected' : '' }}>Madagascar</option>
					<option value="Malawi" {{ $user->country === 'Malawi' ? 'selected' : '' }}>Malawi</option>
					<option value="Malaysia" {{ $user->country === 'Malaysia' ? 'selected' : '' }}>Malaysia</option>
					<option value="Maldives" {{ $user->country === 'Maldives' ? 'selected' : '' }}>Maldives</option>
					<option value="Mali" {{ $user->country === 'Mali' ? 'selected' : '' }}>Mali</option>
					<option value="Malta" {{ $user->country === 'Malta' ? 'selected' : '' }}>Malta</option>
					<option value="Marshall Islands" {{ $user->country === 'Marshall Islands' ? 'selected' : '' }}>Marshall Islands</option>
					<option value="Martinique" {{ $user->country === 'Martinique' ? 'selected' : '' }}>Martinique</option>
					<option value="Mauritania" {{ $user->country === 'Mauritania' ? 'selected' : '' }}>Mauritania</option>
					<option value="Mauritius" {{ $user->country === 'Mauritius' ? 'selected' : '' }}>Mauritius</option>
					<option value="Mayotte" {{ $user->country === 'Mayotte' ? 'selected' : '' }}>Mayotte</option>
					<option value="Mexico" {{ $user->country === 'Mexico' ? 'selected' : '' }}>Mexico</option>
					<option value="Micronesia" {{ $user->country === 'Micronesia' ? 'selected' : '' }}>Micronesia, Federated States of</option>
					<option value="Moldova" {{ $user->country === 'Moldova' ? 'selected' : '' }}>Moldova, Republic of</option>
					<option value="Monaco" {{ $user->country === 'Monaco' ? 'selected' : '' }}>Monaco</option>
					<option value="Mongolia" {{ $user->country === 'Mongolia' ? 'selected' : '' }}>Mongolia</option>
					<option value="Montserrat" {{ $user->country === 'Montserrat' ? 'selected' : '' }}>Montserrat</option>
					<option value="Morocco" {{ $user->country === 'Morocco' ? 'selected' : '' }}>Morocco</option>
					<option value="Mozambique" {{ $user->country === 'Mozambique' ? 'selected' : '' }}>Mozambique</option>
					<option value="Myanmar" {{ $user->country === 'Myanmar' ? 'selected' : '' }}>Myanmar</option>
					<option value="Namibia" {{ $user->country === 'Namibia' ? 'selected' : '' }}>Namibia</option>
					<option value="Nauru" {{ $user->country === 'Nauru' ? 'selected' : '' }}>Nauru</option>
					<option value="Nepal" {{ $user->country === 'Nepal' ? 'selected' : '' }}>Nepal</option>
					<option value="Netherlands" {{ $user->country === 'Netherlands' ? 'selected' : '' }}>Netherlands</option>
					<option value="Netherlands Antilles" {{ $user->country === 'Netherlands Antilles' ? 'selected' : '' }}>Netherlands Antilles</option>
					<option value="New Caledonia" {{ $user->country === 'New Caledonia' ? 'selected' : '' }}>New Caledonia</option>
					<option value="New Zealand" {{ $user->country === 'New Zealand' ? 'selected' : '' }}>New Zealand</option>
					<option value="Nicaragua" {{ $user->country === 'Nicaragua' ? 'selected' : '' }}>Nicaragua</option>
					<option value="Niger" {{ $user->country === 'Niger' ? 'selected' : '' }}>Niger</option>
					<option value="Nigeria" {{ $user->country === 'Nigeria' ? 'selected' : '' }}>Nigeria</option>
					<option value="Niue" {{ $user->country === 'Niue' ? 'selected' : '' }}>Niue</option>
					<option value="Norfolk Island" {{ $user->country === 'Norfolk Island' ? 'selected' : '' }}>Norfolk Island</option>
					<option value="Northern Mariana Islands" {{ $user->country === 'Northern Mariana Islands' ? 'selected' : '' }}>Northern Mariana Islands</option>
					<option value="Norway" {{ $user->country === 'Norway' ? 'selected' : '' }}>Norway</option>
					<option value="Oman" {{ $user->country === 'Oman' ? 'selected' : '' }}>Oman</option>
					<option value="Pakistan" {{ $user->country === 'Pakistan' ? 'selected' : '' }}>Pakistan</option>
					<option value="Palau" {{ $user->country === 'Palau' ? 'selected' : '' }}>Palau</option>
					<option value="Panama" {{ $user->country === 'Panama' ? 'selected' : '' }}>Panama</option>
					<option value="Papua New Guinea" {{ $user->country === 'Papua New Guinea' ? 'selected' : '' }}>Papua New Guinea</option>
					<option value="Paraguay" {{ $user->country === 'Paraguay' ? 'selected' : '' }}>Paraguay</option>
					<option value="Peru" {{ $user->country === 'Peru' ? 'selected' : '' }}>Peru</option>
					<option value="Philippines" {{ $user->country === 'Philippines' ? 'selected' : '' }}>Philippines</option>
					<option value="Pitcairn" {{ $user->country === 'Pitcairn' ? 'selected' : '' }}>Pitcairn</option>
					<option value="Poland" {{ $user->country === 'Poland' ? 'selected' : '' }}>Poland</option>
					<option value="Portugal" {{ $user->country === 'Portugal' ? 'selected' : '' }}>Portugal</option>
					<option value="Puerto Rico" {{ $user->country === 'Puerto Rico' ? 'selected' : '' }}>Puerto Rico</option>
					<option value="Qatar" {{ $user->country === 'Qatar' ? 'selected' : '' }}>Qatar</option>
					<option value="Reunion" {{ $user->country === 'Reunion' ? 'selected' : '' }}>Reunion</option>
					<option value="Romania" {{ $user->country === 'Romania' ? 'selected' : '' }}>Romania</option>
					<option value="Russia" {{ $user->country === 'Russia' ? 'selected' : '' }}>Russian Federation</option>
					<option value="Rwanda" {{ $user->country === 'Rwanda' ? 'selected' : '' }}>Rwanda</option>
					<option value="Saint Kitts and Nevis" {{ $user->country === 'Saint Kitts and Nevis' ? 'selected' : '' }}>Saint Kitts and Nevis</option>
					<option value="Saint LUCIA" {{ $user->country === 'Saint LUCIA' ? 'selected' : '' }}>Saint LUCIA</option>
					<option value="Saint Vincent" {{ $user->country === 'Saint Vincent' ? 'selected' : '' }}>Saint Vincent and the Grenadines</option>
					<option value="Samoa" {{ $user->country === 'Samoa' ? 'selected' : '' }}>Samoa</option>
					<option value="San Marino" {{ $user->country === 'San Marino' ? 'selected' : '' }}>San Marino</option>
					<option value="Sao Tome and Principe" {{ $user->country === 'Sao Tome and Principe' ? 'selected' : '' }}>Sao Tome and Principe</option>
					<option value="Saudi Arabia" {{ $user->country === 'Saudi Arabia' ? 'selected' : '' }}>Saudi Arabia</option>
					<option value="Senegal" {{ $user->country === 'Senegal' ? 'selected' : '' }}>Senegal</option>
					<option value="Seychelles" {{ $user->country === 'Seychelles' ? 'selected' : '' }}>Seychelles</option>
					<option value="Sierra" {{ $user->country === 'Sierra' ? 'selected' : '' }}>Sierra Leone</option>
					<option value="Singapore" {{ $user->country === 'Singapore' ? 'selected' : '' }}>Singapore</option>
					<option value="Slovakia" {{ $user->country === 'Slovakia' ? 'selected' : '' }}>Slovakia (Slovak Republic)</option>
					<option value="Slovenia" {{ $user->country === 'Slovenia' ? 'selected' : '' }}>Slovenia</option>
					<option value="Solomon Islands" {{ $user->country === 'Solomon Islands' ? 'selected' : '' }}>Solomon Islands</option>
					<option value="Somalia" {{ $user->country === 'Somalia' ? 'selected' : '' }}>Somalia</option>
					<option value="South Africa" {{ $user->country === 'South Africa' ? 'selected' : '' }}>South Africa</option>
					<option value="South Georgia" {{ $user->country === 'South Georgia' ? 'selected' : '' }}>South Georgia and the South Sandwich Islands</option>
					<option value="Span" {{ $user->country === 'Span' ? 'selected' : '' }}>Spain</option>
					<option value="SriLanka" {{ $user->country === 'SriLanka' ? 'selected' : '' }}>Sri Lanka</option>
					<option value="St. Helena" {{ $user->country === 'St. Helena' ? 'selected' : '' }}>St. Helena</option>
					<option value="St. Pierre and Miguelon" {{ $user->country === 'St. Pierre and Miguelon' ? 'selected' : '' }}>St. Pierre and Miquelon</option>
					<option value="Sudan" {{ $user->country === 'Sudan' ? 'selected' : '' }}>Sudan</option>
					<option value="Suriname" {{ $user->country === 'Suriname' ? 'selected' : '' }}>Suriname</option>
					<option value="Svalbard" {{ $user->country === 'Svalbard' ? 'selected' : '' }}>Svalbard and Jan Mayen Islands</option>
					<option value="Swaziland" {{ $user->country === 'Swaziland' ? 'selected' : '' }}>Swaziland</option>
					<option value="Sweden" {{ $user->country === 'Sweden' ? 'selected' : '' }}>Sweden</option>
					<option value="Switzerland" {{ $user->country === 'Switzerland' ? 'selected' : '' }}>Switzerland</option>
					<option value="Syria" {{ $user->country === 'Syria' ? 'selected' : '' }}>Syrian Arab Republic</option>
					<option value="Taiwan" {{ $user->country === 'Taiwan' ? 'selected' : '' }}>Taiwan, Province of China</option>
					<option value="Tajikistan" {{ $user->country === 'Tajikistan' ? 'selected' : '' }}>Tajikistan</option>
					<option value="Tanzania" {{ $user->country === 'Tanzania' ? 'selected' : '' }}>Tanzania, United Republic of</option>
					<option value="Thailand" {{ $user->country === 'Thailand' ? 'selected' : '' }}>Thailand</option>
					<option value="Togo" {{ $user->country === 'Togo' ? 'selected' : '' }}>Togo</option>
					<option value="Tokelau" {{ $user->country === 'Tokelau' ? 'selected' : '' }}>Tokelau</option>
					<option value="Tonga" {{ $user->country === 'Tonga' ? 'selected' : '' }}>Tonga</option>
					<option value="Trinidad and Tobago" {{ $user->country === 'Trinidad and Tobago' ? 'selected' : '' }}>Trinidad and Tobago</option>
					<option value="Tunisia" {{ $user->country === 'Tunisia' ? 'selected' : '' }}>Tunisia</option>
					<option value="Turkey" {{ $user->country === 'Turkey' ? 'selected' : '' }}>Turkey</option>
					<option value="Turkmenistan" {{ $user->country === 'Turkmenistan' ? 'selected' : '' }}>Turkmenistan</option>
					<option value="Turks and Caicos" {{ $user->country === 'Turks and Caicos' ? 'selected' : '' }}>Turks and Caicos Islands</option>
					<option value="Tuvalu" {{ $user->country === 'Tuvalu' ? 'selected' : '' }}>Tuvalu</option>
					<option value="Uganda" {{ $user->country === 'Uganda' ? 'selected' : '' }}>Uganda</option>
					<option value="Ukraine" {{ $user->country === 'Ukraine' ? 'selected' : '' }}>Ukraine</option>
					<option value="United Arab Emirates" {{ $user->country === 'United Arab Emirates' ? 'selected' : '' }}>United Arab Emirates</option>
					<option value="United Kingdom" {{ $user->country === 'United Kingdom' ? 'selected' : '' }}>United Kingdom</option>
					<option value="United States" {{ $user->country === 'United States' ? 'selected' : '' }}>United States</option>
					<option value="United States Minor Outlying Islands" {{ $user->country === 'United States Minor Outlying Islands' ? 'selected' : '' }}>United States Minor Outlying Islands</option>
					<option value="Uruguay" {{ $user->country === 'Uruguay' ? 'selected' : '' }}>Uruguay</option>
					<option value="Uzbekistan" {{ $user->country === 'Uzbekistan' ? 'selected' : '' }}>Uzbekistan</option>
					<option value="Vanuatu" {{ $user->country === 'Vanuatu' ? 'selected' : '' }}>Vanuatu</option>
					<option value="Venezuela" {{ $user->country === 'Venezuela' ? 'selected' : '' }}>Venezuela</option>
					<option value="Vietnam" {{ $user->country === 'Vietnam' ? 'selected' : '' }}>Viet Nam</option>
					<option value="Virgin Islands (British)" {{ $user->country === 'Virgin Islands (British)' ? 'selected' : '' }}>Virgin Islands (British)</option>
					<option value="Virgin Islands (U.S)" {{ $user->country === 'Virgin Islands (U.S)' ? 'selected' : '' }}>Virgin Islands (U.S.)</option>
					<option value="Wallis and Futana Islands" {{ $user->country === 'Wallis and Futana Islands' ? 'selected' : '' }}>Wallis and Futuna Islands</option>
					<option value="Western Sahara" {{ $user->country === 'Western Sahara' ? 'selected' : '' }}>Western Sahara</option>
					<option value="Yemen" {{ $user->country === 'Yemen' ? 'selected' : '' }}>Yemen</option>
					<option value="Yugoslavia" {{ $user->country === 'Yugoslavia' ? 'selected' : '' }}>Yugoslavia</option>
					<option value="Zambia" {{ $user->country === 'Zambia' ? 'selected' : '' }}>Zambia</option>
					<option value="Zimbabwe" {{ $user->country === 'Zimbabwe' ? 'selected' : '' }}>Zimbabwe</option>

				</select>
			</div>
          </div>

          <div class="row">
            <div  class="col-xs-12 col-md-6">{{ trans('messages.PostalCode') }}: <input type="text" name="zip" id="zip" class="form-control" value="{{  $user->zip }}" /></div>
          </div>

          <div class="row">
            <div  class="col-xs-12 col-md-6">{{ trans('messages.Picture') }}: 
                @if(!empty($user->picture) && file_exists(base_path() . '/public/images/users/' .  $user->id . '/' . $user->picture)) 
                    <br/><a href="/images/users/{{ $user->id }}/{{  $user->picture }}" target="_blank"><img src="/images/users/{{ $user->id }}/{{  $user->picture }}" alt="face image" height="100" width="100" style="border:1px solid black"></a><br/>
                @else
                    <br/><a href="/images/no_head_img.png" target="_blank"><img src="/images/no_head_img.png" alt="face image" height="100" width="100" style="border:1px solid black"></a><br/>
                @endif
                <input type="text" name="picture" id="picture" class="form-control" value="{{  $user->picture }}" disabled />
                <input type="file" name="imgfile" id="imgfile" value="" />
            </div>
          </div>

          <div class="row">
            <div  class="col-xs-12 col-md-6">{{ trans('messages.Desc') }}: <textarea name="desc" id="desc" class="form-control" rows="3">{{  $user->description }}</textarea></div>
          </div>

          <div class="row">
            <div class="col-xs-12 col-md-6">{{ trans('messages.HourlyRate') }}: <input type="text" name="hourlyrate" id="hourlyrate" class="form-control" value="{{  $user->hourlyrate }}" /></div>
          </div>

          <div class="row">
            <div class="col-xs-12 col-md-6">{{ trans('messages.YearlyRate') }}: <input type="text" name="yearlyrate" id="yearlyrate" class="form-control" value="{{  $user->yearlyrate }}" /></div>
          </div>

          <div class="row">
            <div class="col-xs-12 col-md-6">{{ trans('messages.Token') }}: <input type="text" name="token" id="token" class="form-control" value="{{  $user->token }}" disabled /></div>
          </div>

          <div class="row">
            <div class="col-xs-12 col-md-6">{{ trans('messages.TokenValidTo') }}: <input type="text" name="token_valid_to" id="token_valid_to" class="form-control datetimepicker" value="{{  $user->token_valid_to }}" disabled /></div>
          </div>

          <div class="row">
            <div class="col-xs-12 col-md-6">{{ trans('messages.Created') }}: <input type="text" name="created" id="created" class="form-control datetimepicker" value="{{  $user->created_at }}" disabled /></div>
          </div>

          <div class="row">
            <div class="col-xs-12 col-md-6">{{ trans('messages.LastUpdated') }}: <input type="text" name="last_update" id="last_update" class="form-control datetimepicker" value="{{  $user->updated_at }}" disabled /></div>
          </div>
		  
		  <div class="row">
            <div class="col-xs-12 col-md-6">{{ trans('messages.Ended') }}: <input type="text" name="ended_at" id="ended_at" class="form-control datetimepicker" value="{{  $user->ended_at }}" /></div>
          </div>

          <div class="row">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="submit" name="submit" class="btn btn-primary" value="{{ trans('messages.Submit') }}" />
            <a href="{{ url('/user') }}" class="btn btn-default" target="_self">{{ trans('messages.Cancel') }}</a>
          </div>
      </div>
  </form>
@endsection

