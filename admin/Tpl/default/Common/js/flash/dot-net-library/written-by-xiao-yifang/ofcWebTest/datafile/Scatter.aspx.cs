﻿using System;
using System.Collections.Generic;
using System.Data;
using System.Configuration;
using System.Collections;
using System.Web;
using System.Web.Security;
using System.Web.UI;
using System.Web.UI.WebControls;
using System.Web.UI.WebControls.WebParts;
using System.Web.UI.HtmlControls;
using OpenFlashChart;
using LineHollow=OpenFlashChart.LineHollow;
using ToolTip=OpenFlashChart.ToolTip;
using ToolTipStyle=OpenFlashChart.ToolTipStyle;

public partial class datafile_Scatter : System.Web.UI.Page
{
    protected void Page_Load(object sender, EventArgs e)
    {
        OpenFlashChart.OpenFlashChart chart = new OpenFlashChart.OpenFlashChart();
        List<ScatterValue> data1 = new List<ScatterValue>();
        Random rand = new Random(DateTime.Now.Millisecond);
        for (double i = 0; i < 12; i++)
        {
            data1.Add(new ScatterValue(i,rand.Next(30),5));
        }

        OpenFlashChart.Scatter line1 = new Scatter("#876543",10);
        line1.Values = data1;
        chart.AddElement(line1);

        chart.Title = new Title("Scatter Demo");
        chart.Y_Axis.SetRange(0, 35, 5);
       
        chart.X_Axis.SetRange(0,13);
      
        Response.Clear();
        Response.CacheControl = "no-cache";
        Response.Write(chart.ToString());
        Response.End();
    }
}
