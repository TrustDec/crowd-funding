# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; version 2 of the License.
# 
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# Author: Eugene Kin Chee Yip
# Date:   7 Nov 2008

import	cherrypy
import	socket
import	math

import	openFlashChart
from	openFlashChart_varieties import (Line,
										 Line_Dot,
										 Line_Hollow,
										 Bar,
										 Bar_Filled,
										 Bar_Glass,
										 Bar_3d,
										 Bar_Sketch,
										 HBar,
										 Bar_Stack,
										 Area_Line,
										 Area_Hollow,
										 Pie,
										 Scatter,
										 Scatter_Line)

from	openFlashChart_varieties import (dot_value,
										 hbar_value,
										 bar_value,
										 bar_3d_value,
										 bar_glass_value,
										 bar_sketch_value,
										 bar_stack_value,
										 pie_value,
										 scatter_value,
										 x_axis_labels,
										 x_axis_label)

class OFC:

	@cherrypy.expose
	def index(self):
		graphs = []
		
		# Line Charts
		graphs.append(openFlashChart.flashHTML('100%', '400', '/line', '/flashes/'))
		graphs.append(openFlashChart.flashHTML('100%', '400', '/line_dot', '/flashes/'))
		graphs.append(openFlashChart.flashHTML('100%', '400', '/line_hollow', '/flashes/'))
		
		# Bar Charts
		graphs.append(openFlashChart.flashHTML('100%', '400', '/bar', '/flashes/'))
		graphs.append(openFlashChart.flashHTML('100%', '400', '/bar_filled', '/flashes/'))
		graphs.append(openFlashChart.flashHTML('100%', '400', '/bar_glass', '/flashes/'))
		graphs.append(openFlashChart.flashHTML('100%', '400', '/bar_3d', '/flashes/'))
		graphs.append(openFlashChart.flashHTML('100%', '400', '/bar_sketch', '/flashes/'))
		graphs.append(openFlashChart.flashHTML('100%', '400', '/hbar', '/flashes/'))
		graphs.append(openFlashChart.flashHTML('100%', '400', '/bar_stack', '/flashes/'))
		
		# Area Charts
		graphs.append(openFlashChart.flashHTML('100%', '400', '/area_line', '/flashes/'))
		graphs.append(openFlashChart.flashHTML('100%', '400', '/area_hollow', '/flashes/'))
		
		# Pie Chart
		graphs.append(openFlashChart.flashHTML('100%', '400', '/pie', '/flashes/'))
		
		# Scatter Charts
		graphs.append(openFlashChart.flashHTML('100%', '400', '/scatter', '/flashes/'))
		graphs.append(openFlashChart.flashHTML('100%', '400', '/scatter_line', '/flashes/'))
		
		# Radar Charts
		graphs.append(openFlashChart.flashHTML('100%', '400', '/radar', '/flashes/'))

		# Testing Chart
		graphs.append(openFlashChart.flashHTML('100%', '400', '/test', '/flashes/'))
		
		graphs.append(self.source("html_snippet.html"))
	
		return self.source("OFC.htm") %({"chart": "<br/><br/><br/><br/>".join(graphs)})
		
		
	# Line Charts
	@cherrypy.expose
	def line(self):
		plot1 = Line(text = "line1", fontsize = 20, values = range(0,10))
		plot2 = Line(text = "line2", fontsize = 06, values = range(10,0, -1))
		plot3 = Line(text = "line3", fontsize = 12, values = range(-5,5))
		
		plot1.set_line_style(4, 3)
		plot2.set_line_style(5, 5)
		plot3.set_line_style(4, 8)
		
		plot1.set_colour('#D4C345')
		plot2.set_colour('#C95653')
		plot3.set_colour('#8084FF')
		
		chart = openFlashChart.template("Line chart")
		chart.set_y_axis(min = -6, max = 10)
		
		chart.add_element(plot1)
		chart.add_element(plot2)
		chart.add_element(plot3)

		return chart.encode()
		
	@cherrypy.expose
	def line_dot(self):
		plot1 = Line_Dot(values = [math.sin(float(x)/10) * 1.9 + 4 for x in range(0, 62, 2)])
		plot2 = Line_Dot(values = [math.sin(float(x)/10) * 1.9 + 7 for x in range(0, 62, 2)])
		plot3 = Line_Dot(values = [math.sin(float(x)/10) * 1.9 + 10 for x in range(0, 62, 2)])
		
		plot1.set_halo_size(0)
		plot2.set_halo_size(1)
		plot3.set_halo_size(3)
		
		plot1.set_width(1)
		plot2.set_width(2)
		plot3.set_width(6)
		
		plot1.set_dot_size(4)
		plot2.set_dot_size(4)
		plot3.set_dot_size(6)
		
		chart = openFlashChart.template("Line_Dot chart")
		chart.set_y_axis(min = 0, max = 15, steps = 5)
		chart.add_element(plot1)
		chart.add_element(plot2)
		chart.add_element(plot3)
		
		return chart.encode()

	@cherrypy.expose
	def line_hollow(self):
		plot1 = Line_Hollow(values = [math.sin(float(x)/10) * 1.9 + 4 for x in range(0, 62, 2)])
		plot2 = Line_Hollow(values = [math.sin(float(x)/10) * 1.9 + 7 for x in range(0, 62, 2)])
		plot3 = Line_Hollow(values = [math.sin(float(x)/10) * 1.9 + 10 for x in range(0, 62, 2)])
		
		plot1.set_halo_size(3)
		plot2.set_halo_size(1)
		plot3.set_halo_size(0)
		
		plot1.set_width(1)
		plot2.set_width(2)
		plot3.set_width(3)
		
		plot1.set_dot_size(4)
		plot2.set_dot_size(4)
		plot3.set_dot_size(6)
		
		chart = openFlashChart.template("Line_Hollow chart")
		chart.set_y_axis(min = 0, max = 15, steps = 5)
		chart.add_element(plot1)
		chart.add_element(plot2)
		chart.add_element(plot3)
		
		return chart.encode()
		
		
	# Bar Charts
	@cherrypy.expose
	def bar(self):
		plot = Bar(text = "bar1", values = range(9, 0, -1))
		
		chart = openFlashChart.template("Bar chart")
		chart.add_element(plot)

		return chart.encode()	

	@cherrypy.expose
	def bar_filled(self):
		plot = Bar_Filled(values = range(9, 0, -1) + [bar_value((5, 3), '#AAAAAA', 'Special:<br>Top = #top#<br>Bottom = #bottom#')], colour = '#E2D66A', outline = '#577261')
		
		chart = openFlashChart.template("Bar_Filled chart",)
		chart.add_element(plot)
		chart.set_bg_colour('#FFFFFF')

		return chart.encode()
		
	@cherrypy.expose
	def bar_glass(self):
		plot = Bar_Glass(values = range(-5, 5, 2) + [bar_glass_value(5, '#333333', 'Special:<br>Top = #top#<br>Bottom = #bottom#')])
		
		chart = openFlashChart.template("Bar_Glass chart")
		chart.set_y_axis(min = -6, max = 6)
		chart.add_element(plot)
		
		return chart.encode()
		
	@cherrypy.expose
	def bar_3d(self):
		plot = Bar_3d(values = range(-8, 8, 2) + [bar_3d_value(5, '#333333', 'Special:<br>Top = #top#<br>Bottom = #bottom#')])
		plot.set_colour('#D54C78')
		
		chart = openFlashChart.template("Bar_3d chart")
		chart.set_y_axis(min = -8, max = 8)
		chart.set_x_axis(colour = '#909090', three_d = 5, labels = list('qp#m^fur'))
		chart.add_element(plot)
		
		return chart.encode()
		
	@cherrypy.expose
	def bar_sketch(self):
		plot1 = Bar_Sketch(values = range(10, 0, -1) + [bar_sketch_value(5, '#333333', 'Special:<br>Top = #top#')], colour = '#81AC00', outline = '#567300')
		plot2 = Bar_Sketch(values = [bar_sketch_value(5, '#333333', 'Special:<br>Top = #top#')] + range(10, 0, -1), colour = '#81ACFF', outline = '#5673FF')
		
		chart = openFlashChart.template("Bar_Sketch chart", style = '{color: #567300; font-size: 14px}')
		chart.add_element(plot1)
		chart.add_element(plot2)
		
		return chart.encode()

	@cherrypy.expose
	def hbar(self):
		months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
	
		plot = HBar(colour = '#86BBEF')
		plot.set_tooltip('Months: #val#')
		plot.append_values(hbar_value((0, 4), colour = '#909090'))
		plot.append_values(hbar_value((4, 8), colour = '#909009'))
		plot.append_values(hbar_value((8, 11), tooltip = '#left# to #right#<br>%s to %s (#val# months)' %(months[8], months[11])))
		
		chart = openFlashChart.template("HBar chart")
		chart.set_x_axis(offset = False, labels = x_axis_labels(labels = months))
		chart.set_y_axis(offset = True, labels = ['one', 'two', 'three'])
		chart.add_element(plot)
		
		return chart.encode()

	@cherrypy.expose
	def bar_stack(self):
		plot = Bar_Stack(colours = ['#C4D318', '#50284A', '#7D7B6A'])
		plot.set_tooltip('X label [#x_label#], Value [#val#]<br>Total [#total#]')
		plot.append_keys('#C4D318', 'job1', 13)
		plot.append_keys('#50284A', 'job2', 13)
		plot.append_keys('#7D7B6A', 'job3', 13)
		plot.append_keys('#ff0000', 'job4', 13)
		plot.append_keys('#ff00ff', 'job5', 13)
		
		plot.append_stack([2.5, 5, 2.5])
		plot.append_stack([2.5, 5, 1.25, 1.25])
		plot.append_stack([5, bar_stack_value(5, '#ff0000')])
		plot.append_stack([2, 2, 2, 2, bar_stack_value(2, '#ff00ff')])
		
		chart = openFlashChart.template("Bar_Stack chart", style = '{font-size: 20px; color: #F24062; text-align: center;}')
		chart.set_tooltip(behaviour = 'hover')
		chart.set_x_axis(labels = x_axis_labels(labels = ['Winter', 'Spring', 'Summer', 'Autumn']))
		chart.set_y_axis(min = 0, max = 14, steps = 2)
		chart.add_element(plot)
		
		return chart.encode()


	# Area charts
	@cherrypy.expose
	def area_line(self):
		plot = Area_Line(colour = '#C4B86A', fill = '#C4B86A', fill_alpha = 0.7, values = [math.sin(float(x)/10) * 1.9 for x in range(0, 62, 2)])
		plot.set_halo_size(1)
		plot.set_width(2)
		plot.set_dot_size(4)
		
		chart = openFlashChart.template("Area_Line chart")
		chart.set_y_axis(min = -2, max = 2, steps = 2, offset = True)
		chart.set_x_axis(labels = x_axis_labels(labels = ['%d' %i for i in range(0, 62, 2)], steps = 4, rotate = 'vertical'), steps = 2)
		chart.add_element(plot)
		
		return chart.encode()

	@cherrypy.expose
	def area_hollow(self):
		plot = Area_Hollow(colour = '#838A96', fill = '#E01B49', fill_alpha = 0.4, values = [math.sin(float(x)/10) * 1.9 for x in range(0, 62, 2)])
		plot.set_halo_size(1)
		plot.set_width(2)
		plot.set_dot_size(4)
		
		chart = openFlashChart.template("Area_Hollow chart")
		chart.set_y_axis(min = -2, max = 2, steps = 2, offset = False)
		chart.set_x_axis(labels = x_axis_labels(rotate = 'diagonal'), steps = 2)
		chart.add_element(plot)
		
		return chart.encode()
		
		
	# Pie chart
	@cherrypy.expose
	def pie(self):
		plot = Pie(start_angle = 35, animate = True, values = [2, 3, pie_value(6.5, ('hello (6.5)', '#FF33C9', 24))], colours = ['#D01F3C', '#356AA0', '#C79810'], label_colour = '#432BAF')
		plot.set_tooltip('#val# of #total#<br>#percent# of 100%')
		plot.set_gradient_fill(True)
		plot.set_on_click('plot1')
		plot.set_no_labels(False)
		
		chart = openFlashChart.template("Pie chart")
		chart.add_element(plot)
		
		return chart.encode()
		

	# Scatter charts
	@cherrypy.expose
	def scatter(self):
		radians = [math.radians(degree) for degree in xrange(0, 360, 5)]
		values = [scatter_value(('%.2f' %math.sin(radian), '%.2f' %math.cos(radian))) for radian in radians]
	
		plot1 = Scatter(colour = '#FFD600', values = [scatter_value((0, 0))])
		plot2 = Scatter(colour = '#D600FF', values = values)
		
		plot1.set_dot_size(10)
		plot2.set_dot_size(3)
		
		chart = openFlashChart.template("Scatter chart")
		chart.set_x_axis(min = -2, max = 3)
		chart.set_y_axis(min = -2, max = 2)
		chart.add_element(plot1)
		chart.add_element(plot2)
		
		return chart.encode()

	@cherrypy.expose
	def scatter_line(self):
		from random import randint
		
		x_values = [0]
		while x_values[-1] < 25:
			x_values.append(x_values[-1] + float(randint(5, 15))/10)
			
		values = [scatter_value((x, float(randint(-15, 15))/10)) for x in x_values]
	
		plot = Scatter_Line(colour = '#FFD600', values = values)
		plot.set_dot_size(3)
		
		chart = openFlashChart.template("Scatter_Line chart")
		chart.set_x_axis(min = 0, max = 25)
		chart.set_y_axis(min = -10, max = 10)
		chart.add_element(plot)
		
		return chart.encode()
		
		
	# Radar Charts
	@cherrypy.expose
	def radar(self):
		plot = Area_Hollow(colour = '#45909F', fill = '#45909F', fill_alpha = 0.4, values = [3, 4, 5, 4, 3, 3, 2.5])
		plot.set_width(1)
		plot.set_dot_size(4)
		plot.set_halo_size(1)
		plot.set_loop()
	
		chart = openFlashChart.template("Radar chart")
		chart.set_bg_colour('#DFFFEC')
		chart.set_radar_axis(max = 5, colour = '#EFD1EF', grid_colour = '#EFD1EF', labels = list('012345'), spoke_labels = list('1234567'))
		chart.set_tooltip(behaviour = 'proximity')
		chart.add_element(plot)

		return chart.encode()


	# Testing Graph
	@cherrypy.expose
	def test(self):
		plot1 = Line(text = "line1", fontsize = 20, values = [None, 5, 1, 2, 4, None, None, 2, 7, 5])
		plot2 = Line(text = "line2", fontsize = 12, values = range(-4, 7, 1))
		plot3 = Bar_Glass(text = "bar1", values = [4, None, -4, 3, bar_glass_value((5, -2), '#333333', 'Special:<br>Top = #top#<br>Bottom = #bottom#'), 7, None, None, -5, 5])
				
		plot1.set_tooltip('Title1:<br>Amount = #val#')
		plot2.set_tooltip('Title2:<br>Value = #val#')
		plot3.set_tooltip('Title3:<br>Height = #val#')
		
		plot1.set_on_click('plot1')
		plot2.set_on_click('plot2')
		
		plot1.set_line_style(4, 3)
		plot2.set_line_style(4, 8)
		
		plot1.set_colour('#D4C345')
		plot2.set_colour('#8084FF')
		plot3.set_colour('#FF84FF')
		
		chart = openFlashChart.template("Testing chart", style = '{font-size: 40px; font-family: Times New Roman; color: #A2ACBA; text-align: right;}')

		chart.set_x_axis(stroke = 10, colour = '#165132', tick_height = 30, grid_colour = '#AAEE00', offset = True, steps = 2, labels = x_axis_labels(labels = list('sfwertr56w') + [x_axis_label('custom!!', '#2683CF', 24, 'diagonal')], steps = 2))
		chart.set_y_axis(stroke = 5, colour = '#1E33FF', tick_length = 15, grid_colour = '#090305', offset = True, steps = 4, min = -6)
		chart.set_y_axis_right(stroke = 5, colour = '#44FF22', tick_length = 20, grid_colour = '#55ff55', offset = True, steps = 1)

		chart.set_x_legend("x-axis legend", style = '{font-size: 20px; color: #778877}')
		chart.set_y_legend("y-axis legend", style = '{font-size: 22px; color: #778877}')

		chart.set_tooltip(shadow = True, stroke = 4, colour = '#909090', bg_colour = '#FAFAFA', title_style = '{font-size: 14px; color: #CC2A43;}', body_style = '{font-size: 10px; font-weight: bold; color: #000000;}')
		
		chart.add_element(plot1)
		chart.add_element(plot2)
		chart.add_element(plot3)

		return chart.encode()

	@cherrypy.expose
	def ajax(self, count):
		if int(count) % 3 is 0:
			plot = Line_Dot(text = "line1", fontsize = 20, values = [None, 5, dot_value(1, '#D02020', '#val#<br>Text'), 2, 4, None, None, 2, 7, 5])
		elif int(count) % 3 is 1:
			plot = Line(text = "line2", fontsize = 12, values = range(-4, 7, 1))
		else:
			plot = Bar_Glass(text = "bar1", values = [4, None, -4, 3, bar_glass_value((5, -2), '#333333', 'Special:<br>Top = #top#<br>Bottom = #bottom#'), 7, None, None, -5, 5])
				
		plot.set_tooltip('Title1:<br>Amount = #val#')
		plot.set_on_click('plot1')
		plot.set_line_style(4, 3)
		
		plot.set_colour('#D4C345')
		
		chart = openFlashChart.template("Testing chart: %s" %count, style = '{font-size: 40px; font-family: Times New Roman; color: #A2ACBA; text-align: right;}')

		chart.set_x_axis(stroke = 10, colour = '#165132', tick_height = 30, grid_colour = '#AAEE00', offset = True, steps = 2, labels = x_axis_labels(labels = list('sfwertr56w') + [x_axis_label('custom!!', '#2683CF', 24, 210)], steps = 2))
		chart.set_y_axis(stroke = 5, colour = '#1E33FF', tick_length = 15, grid_colour = '#090305', offset = True, steps = 4, min = -6)
		chart.set_y_axis_right(stroke = 5, colour = '#44FF22', tick_length = 20, grid_colour = '#55ff55', offset = True, steps = 1)

		chart.set_x_legend("x-axis legend", style = '{font-size: 20px; color: #778877}')
		chart.set_y_legend("y-axis legend", style = '{font-size: 22px; color: #778877}')

		chart.set_tooltip(shadow = True, stroke = 4, colour = '#909090', bg_colour = '#FAFAFA', title_style = '{font-size: 14px; color: #CC2A43;}', body_style = '{font-size: 10px; font-weight: bold; color: #000000;}')
		
		chart.add_element(plot)

		return chart.encode()
		
		
		
		

	def source(self, filename):
		"""Opens a file specified by the file/pathname in read-only"""
		file = open(filename, 'r')
		result = file.read()
		file.close()
		return result

	@cherrypy.expose
	def flashes(self, filename):
		cherrypy.response.headers['Content-Type'] = "application/x-shockwave-flash"
		cherrypy.response.headers['Expires'] = "Tue, 01 Dec 2009 12:00:00 GMT"
		cherrypy.response.headers['Cache-Control'] = "Public"
		return open(filename)


cherrypy.server.socket_host = socket.gethostbyname(socket.gethostname())
cherrypy.quickstart(OFC(), config = 'serverconfig.conf')

