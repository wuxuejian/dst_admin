<div>

</div>
<div>
    <ul id="easyui-tree-drbac-mca-controller-list"></ul> 
</div>
<script>
	var DrbacMcaControllerList = new Object();
	DrbacMcaControllerList.init = function(){
		$('#easyui-tree-drbac-mca-controller-list').tree({     
		    data: <?php echo json_encode($tree); ?> 
		}); 
	};
	DrbacMcaControllerList.init();
</script>