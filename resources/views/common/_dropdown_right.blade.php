<select class="right-language" name="lang">
    @foreach($language as $key => $lang)
         <option value="{{$lang}}">{{strtoupper($lang)}}</option>
    @endforeach
</select>

