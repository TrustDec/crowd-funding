# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; version 2 of the License.
# 
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
# 
# Author: Emanuel Fonseca
# Email:  emdfonseca<at>gmail<dot>com
# Date:   25 August 2008
#
# Author: Eugene Kin Chee Yip
# Date:   7 Nov 2008

import	cjson

from	openFlashChart_elements import (title,
										x_legend,
										y_legend,
										x_axis,
										y_axis,
										radar_axis,
										tooltip)


def flashHTML(width, height, url, ofc_base_url="/flashes/", ofc_swf="OFC.swf" ):
	return (
		"""
		<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000"
				codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,115,0"
				width="%(width)s" height="%(height)s" id="chart" align="middle">
			<param name="allowScriptAccess" value="sameDomain"/>
			<param name="movie" value="%(ofc_base_url)s%(ofc_swf)s"/>
			<param name="FlashVars" value="data-file=%(url)s"/>
			<param name="quality" value="high"/>
			<param name="bgcolor" value="#FFFFFF"/>
			<embed src="%(ofc_base_url)s%(ofc_swf)s" FlashVars="data-file=%(url)s" quality="high" bgcolor="#FFFFFF"
				   width=%(width)s height=%(height)s name="chart" align="middle" allowScriptAccess="sameDomain"
				   type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer"/>
		</object>
		""") % locals()



class template(dict):
	def __init__(self, title_string, style = None):
		self['title'] = title(title_string, style)

	def set_x_legend(self, legend, style):
		self['x_legend'] = x_legend(legend, style)

	def set_y_legend(self, legend, style):
		self['y_legend'] = y_legend(legend, style)

	def set_x_axis(self, stroke = None, tick_height = None, colour = None, grid_colour = None, labels = None, three_d = None, max = None, min = None, steps = None, offset = None):
		self['x_axis'] = x_axis(stroke, tick_height, colour, grid_colour, labels, three_d, max, min, steps, offset)
    
	def set_y_axis(self, stroke = None, tick_length = None, colour = None, grid_colour = None, labels = None, max = None, min = None, steps = None, offset = None):
		self['y_axis'] = y_axis(stroke, tick_length, colour, grid_colour, labels, max, min, steps, offset)
    
	def set_y_axis_right(self, stroke = None, tick_length = None, colour = None, grid_colour = None, labels = None, max = None, min = None, steps = None, offset = None):
		self['y_axis_right'] = y_axis(stroke, tick_length, colour, grid_colour, labels, max, min, steps, offset)

	def set_radar_axis(self, stroke = None, tick_height = None, colour = None, grid_colour = None, labels = None, max = None, min = None, steps = None, spoke_labels = None):
		self['radar_axis'] = radar_axis(stroke, tick_height, colour, grid_colour, labels, max, min, steps, spoke_labels)


	def set_bg_colour(self, colour):
		self['bg_colour'] = colour

	def set_tooltip(self, shadow = None, stroke = None, colour = None, bg_colour = None, title_style = None, body_style = None, behaviour = None):
		self['tooltip'] = tooltip(shadow, stroke, colour, bg_colour, title_style, body_style, behaviour)

	def add_element(self, element):
		try:
			self['elements'].append(element)
		except:
			self['elements'] = [element]

	def encode(self):
		return cjson.encode(self)
        
        
        
        

