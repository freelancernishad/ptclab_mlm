<style>
    span.tf-nc {
    padding: 0 !important;
    border-radius: 50%;
}
span.tf-nc img {
    width: 50px;
    height: 50px;
    border-radius: 50%;
}


.treePopup {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: #000000a6;
    z-index: 999;
}

.treePopupContiner {
    max-width: 333px;
    min-width: 336px;
    background: white;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    border-radius: 10px;
}

.treePoupHeader {
    position: relative;
    text-align: right;
    padding: 2px 11px;
    font-size: 20px;
    border-bottom: 1px solid #a7a2a2;
}

.treePoupHeader span {
    color: red;
    padding: 1px 8px;
    background: #ffcece;
    cursor: pointer;
}

.treePopupBody {
    padding: 7px 10px;
}
span.tf-nc {
    cursor: pointer;
}


/* ul#tree-container ul {
    width: 100%;
} */

ul#tree-container {
    width: 100%;
}

ul#tree-container li {
    width: 100%;
}
ul#tree-container ul li {
    width: 100%;
}


</style>





<div class="tf-tree example">
    <ul id="tree-container">

        @include('laravelTreeView',['tree'=>$tree])

    </ul>
  </div>



  <div class="treePopup" id="treePopup" style="display:none">
    <div class="treePopupContiner">
        <div class="treePoupHeader">
            <span id="ClosePopup" onclick="closePopup()">X</span>
        </div>
        <div class="treePopupBody">
            <h4>Name: <span id="TreeName"></span></h4>
            <p>Username: <span id="TreeUsername"></span></p>
        </div>
    </div>
</div>


  <script>








    function viewUserDetials(username,name,lastname){

        document.getElementById('treePopup').style.display="block"

        document.getElementById('TreeName').innerHTML = name+' '+lastname;
        document.getElementById('TreeUsername').innerHTML = username;

    }

    function closePopup(){
        document.getElementById('treePopup').style.display="none"
    }

    function showthisuser(id){
        console.log(id)
    }


  </script>
