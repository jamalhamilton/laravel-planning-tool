jQuery.fn.dataTable.Api.register('row.addByPos()', function(data, index,contextDataTable) {
    var currentPage = this.page();
    var id = 0;

    //insert the row
    this.row.add(data);
    this.draw();
    $(contextDataTable).find("tr:last-child").attr('data-id',id);
    //move added row to desired index
    var rowCount = this.data().length-1,
        insertedRow = this.row(rowCount).data(),
        tempRow,tempId;

    for (var i=rowCount;i>=index;i--) {

        tempRow = this.row(i-1).data();
        this.row(i-1).data(insertedRow);
        this.row(i).data(tempRow);

    }

    //refresh the current page
    this.draw();
    //updaate id
    var num_row = $(contextDataTable).find("tr").length;
    console.log("num_row",num_row)
    for(var i = num_row-1; i >index; i--){
        var idr = $(contextDataTable).find("tr:nth-child("+(i-1)+")").attr('data-id');
        console.log(i,idr)
        $(contextDataTable).find("tr:nth-child("+(i)+")").attr('data-id',idr);
    }

    return this;
});