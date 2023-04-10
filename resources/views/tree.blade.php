@php
if(count($tree->children) > 1){
        $underline = 'underline';
    }else{
    $underline = '';

}
    @endphp
<div class="tree">
    <div class="node level-{{ $level }} {{ $underline }}">
         <div class="parent">
             <div class="children {{ $borderBottom.$level.$key }}">
                 <img src="https://www.w3schools.com/howto/img_avatar.png" alt="">
                <span>{{ $tree->user->username }}</span>
            </div>
        </div>
        @if(count($tree->children) > 0)

            <div class="children">
                @foreach($tree->children as $key=>$child)
                <style>
                    <?php echo ".borderbottom".$level.$key."::after" ?> {
                       content: "";
                       position: absolute;
                       top: 99px;
                       left: 99px;
                       width: 1px;
                       height: 37px;
                       background: black;
                   }
                       </style>
                    @include('tree', ['tree' => $child, 'level' => $level + 1,'key'=>$key])
                @endforeach
            </div>
        @endif
    </div>
</div>
