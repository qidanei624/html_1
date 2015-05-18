var Typecho= {};
Typecho.guid= function(c,d) {
	var b=$(c);
	if(null==b) {
		return
	}
	var h=b.getElements("dt");
	var f=b.getElements("dd");
	var a=null,e=null,i=null;
	var g= {
		reSet: function() {
			h.removeClass("current");
			f.setStyle("display","none")
		},
		popUp: function(k) {
			k=a=$(k)||k;
			k.addClass("current");
			var j=k.getNext("dd");
			if(j) {
				j.setStyle("left",k.getPosition().x-k.getParent("dl").getPosition().x-d.offset);
				if(j.getStyle("display")!="none") {
					j.setStyle("display","none")
				} else {
					j.setStyle("display","block");
					j.getElement("ul li:first-child").setStyle("border-top","none");
					j.getElement("ul li:last-child").setStyle("border-bottom","none");
					j.getElements("ul li").setStyle("width",j.getCoordinates().width-22)
				}
			}
		}
	};
	if(d.type=="mouse") {
		h.addEvent("mouseenter", function(j) {
			e=$clear(e);
			g.reSet();
			if(j.target.nodeName.toLowerCase()=="a") {
				j.target=$(j.target).getParent("dt")
			}
			g.popUp(j.target)
		});
		h.addEvent("mouseout", function(j) {
			if(!e) {
				e=g.reSet.delay(500)
			}
		});
		f.addEvent("mouseenter", function(j) {
			if(e) {
				e=$clear(e)
			}
		});
		f.addEvent("mouseleave", function(j) {
			if(!e) {
				e=g.reSet.delay(50)
			}
		})
	}
	if(d.type=="click") {
		h.addEvent("click", function(j) {
			g.reSet();
			if(j.target.nodeName.toLowerCase()=="a") {
				j.target=$(j.target).getParent("dt")
			}
			g.popUp(j.target);
			j.stop()
		});
		$(document).addEvent("click",g.reSet)
	}
	return g
};
Typecho.Table= {
	table:null,
	draggable:false,
	draggedEl:null,
	draggedFired:false,
	init: function(a) {
		$(document).getElements(a).each( function(b) {
			Typecho.Table.table=b;
			Typecho.Table.draggable=b.hasClass("draggable");
			Typecho.Table.bindButtons();
			Typecho.Table.reset()
		})
	},
	reset: function() {
		var c=Typecho.Table.table;
		Typecho.Table.draggedEl=null;
		if("undefined"==typeof(c._childTag)) {
			switch(c.get("tag")) {
				case"ul":
					c._childTag="li";
					break;
				case"table":
					c._childTag="tr";
					break;
				default:
					break
			}var b=c.getElements(c._childTag+" input[type=checkbox]").each( function(d) {
				d._parent=d.getParent(Typecho.Table.table._childTag);
				d.addEvent("click",Typecho.Table.checkBoxClick)
			})
		}
		var a=c.getElements(c._childTag+".even").length>0;
		c.getElements(c._childTag).filter( function(e,d) {
			return"tr"!=e.get("tag")||0==e.getChildren("th").length
		}).each( function(e,d) {
			if(a) {
				if(d%2) {
					e.removeClass("even")
				} else {
					e.addClass("even")
				}
				if(e.hasClass("checked")||e.hasClass("checked-even")) {
					e.removeClass(d%2?"checked-even":"checked").addClass(d%2?"checked":"checked-even")
				}
			}
			Typecho.Table.bindEvents(e)
		})
	},
	checkBoxClick: function(a) {
		var b=$(this);
		if(b.getProperty("checked")) {
			b.setProperty("checked",false);
			b._parent.removeClass(b._parent.hasClass("even")?"checked-even":"checked");
			Typecho.Table.unchecked(this,b._parent)
		} else {
			b.setProperty("checked",true);
			b._parent.addClass(b._parent.hasClass("even")?"checked-even":"checked");
			Typecho.Table.checked(this,b._parent)
		}
	},
	itemMouseOver: function(a) {
		if(!Typecho.Table.draggedEl||Typecho.Table.draggedEl==this) {
			$(this).addClass("hover");
			if(Browser.Engine.trident) {
				$(this).getElements(".hidden-by-mouse").setStyle("display","inline")
			}
		}
	},
	itemMouseLeave: function(a) {
		if(!Typecho.Table.draggedEl||Typecho.Table.draggedEl==this) {
			$(this).removeClass("hover");
			if(Browser.Engine.trident) {
				$(this).getElements(".hidden-by-mouse").setStyle("display","none")
			}
		}
	},
	itemClick: function(a) {
		var b;
		if(b=$(this).getElement("input[type=checkbox]")) {
			b.fireEvent("click")
		}
	},
	itemMouseDown: function(a) {
		if(!Typecho.Table.draggedEl) {
			Typecho.Table.draggedEl=this;
			Typecho.Table.draggedFired=false;
			return false
		}
	},
	itemMouseMove: function(a) {
		if(Typecho.Table.draggedEl) {
			if(!Typecho.Table.draggedFired) {
				Typecho.Table.dragStart(this);
				$(this).setStyle("cursor","move");
				Typecho.Table.draggedFired=true
			}
			if(Typecho.Table.draggedEl!=this) {
				if($(this).getCoordinates(Typecho.Table.draggedEl).top<0) {
					$(this).inject(Typecho.Table.draggedEl,"after")
				} else {
					$(this).inject(Typecho.Table.draggedEl,"before")
				}
				if($(this).hasClass("even")) {
					if(!$(Typecho.Table.draggedEl).hasClass("even")) {
						$(this).removeClass("even");
						$(Typecho.Table.draggedEl).addClass("even")
					}
					if($(this).hasClass("checked-even")&&!$(Typecho.Table.draggedEl).hasClass("checked-even")) {
						$(this).removeClass("checked-even");
						$(Typecho.Table.draggedEl).addClass("checked-even")
					}
				} else {
					if($(Typecho.Table.draggedEl).hasClass("even")) {
						$(this).addClass("even");
						$(Typecho.Table.draggedEl).removeClass("even")
					}
					if($(this).hasClass("checked")&&$(Typecho.Table.draggedEl).hasClass("checked")) {
						$(this).removeClass("checked");
						$(Typecho.Table.draggedEl).addClass("checked")
					}
				}
				return false
			}
		}
	},
	itemMouseUp: function(d) {
		if(Typecho.Table.draggedEl) {
			var c=Typecho.Table.table.getElements(Typecho.Table.table._childTag+" input[type=checkbox]");
			var a="";
			for(var b=0;b<c.length;b++) {
				if(a.length>0) {
					a+="&"
				}
				a+=c[b].name+"="+c[b].value
			}
			if(Typecho.Table.draggedFired) {
				$(this).fireEvent("click");
				$(this).setStyle("cursor","");
				Typecho.Table.dragStop(this,a);
				Typecho.Table.draggedFired=false;
				Typecho.Table.reset()
			}
			Typecho.Table.draggedEl=null;
			return false
		}
	},
	checked: function(a,b) {
		return false
	},
	unchecked: function(a,b) {
		return false
	},
	dragStart: function(a) {
		return false
	},
	dragStop: function(b,a) {
		return false
	},
	bindButtons: function() {
		$(document).getElements(".typecho-table-select-all").addEvent("click", function() {
			Typecho.Table.table.getElements(Typecho.Table.table._childTag+" input[type=checkbox]").each( function(a) {
				if(!a.getProperty("checked")) {
					a.fireEvent("click")
				}
			})
		});
		$(document).getElements(".typecho-table-select-none").addEvent("click", function() {
			Typecho.Table.table.getElements(Typecho.Table.table._childTag+" input[type=checkbox]").each( function(a) {
				if(a.getProperty("checked")) {
					a.fireEvent("click")
				}
			})
		});
		$(document).getElements(".typecho-table-select-submit").addEvent("click", function() {
			var b=this.get("lang");
			var a=b?confirm(b):true;
			if(a) {
				var c=Typecho.Table.table.getParent("form");
				c.getElement("input[name=do]").set("value",$(this).getProperty("rel"));
				c.submit()
			}
		})
	},
	bindEvents: function(a) {
		a.removeEvents();
		a.addEvents({
			mouseover:Typecho.Table.itemMouseOver,
			mouseleave:Typecho.Table.itemMouseLeave,
			click:Typecho.Table.itemClick
		});
		if(Typecho.Table.draggable&&Typecho.Table.table.getElements(Typecho.Table.table._childTag+" input[type=checkbox]").length>0) {
			a.addEvents({
				mousedown:Typecho.Table.itemMouseDown,
				mousemove:Typecho.Table.itemMouseMove,
				mouseup:Typecho.Table.itemMouseUp
			})
		}
	}
};
Typecho.message= function(b) {
	var a=$(document).getElement(b);
	setTimeout( function() {
		if(a) {
			var c=new Fx.Morph(a, {
				duration:"short",
				transition:Fx.Transitions.Sine.easeOut
			});
			c.addEvent("complete", function() {
				this.element.style.display="none"
			});
			c.start({
				"margin-top":[30,0],
				height:[21,0],
				opacity:[1,0]
			})
		}
	},5000)
};
Typecho.openLink= function(b,a) {
	$(document).getElements("a").each( function(d) {
		var c=d.href;
		if(c&&"#"!=c) {
			$(d).addEvent("click", function(f) {
				var g=this.get("lang");
				var e=g?confirm(g):true;
				if(!e) {
					f.stop()
				}
			});
			if(b.exec(c)||a.exec(c)) {
				return
			}
			$(d).addEvent("click", function() {
				window.open(this.href);
				return false
			})
		}
	})
};
Typecho.scroll= function(b,a) {
	var d=$(document).getElement(b);
	if(d) {var c=new Fx.Scroll(window).toElement(d.getParent(a))
	}
};
Typecho.location= function(a) {
	setTimeout('window.location.href="'+a+'"',0)
};
Typecho.toggleEl=null;
Typecho.toggleBtn=null;
Typecho.toggleHideWord=null;
Typecho.toggleOpened=false;
Typecho.toggle= function(e,c,b,a) {
	var d=$(document).getElement(e);
	if(null!=Typecho.toggleBtn&&c!=Typecho.toggleBtn) {
		$(Typecho.toggleBtn).set("html",Typecho.toggleHideWord);
		Typecho.toggleEl.setStyle("display","none");
		Typecho.toggleEl.fireEvent("tabHide");
		$(Typecho.toggleBtn).toggleClass("close")
	}
	$(c).toggleClass("close");
	if("none"==d.getStyle("display")) {
		$(c).set("html",b);
		d.setStyle("display","block");
		d.fireEvent("tabShow");
		Typecho.toggleOpened=true
	} else {
		$(c).set("html",a);
		d.setStyle("display","none");
		d.fireEvent("tabHide");
		Typecho.toggleOpened=false
	}
	Typecho.toggleEl=d;
	Typecho.toggleBtn=c;
	Typecho.toggleHideWord=a
};
Typecho.textareaHasPrepare=false;
Typecho.textareaAdd= function(e,k,j) {
	var h=$(document).getElement(e);
	var b,d,f,g;
	b=h.scrollTop;
	if(typeof(h.selectionStart)=="number") {
		h.focus();
		d=h.selectionStart;
		f=h.selectionEnd
	} else {
		if(document.selection) {
			h.focus();
			g=document.selection.createRange()
		}
	}
	if(typeof(h.selectionStart)=="number") {
		var c=h.value.substr(0,d);
		var i=h.value.substr(f);
		var a=h.value.substr(d,f-d);
		h.value=c+k+a+j+i;
		h.setSelectionRange(d+k.length,d+k.length)
	} else {
		if(document.selection) {
			if(g.text.length>0) {
				g.text=k+g.text+j
			} else {
				g.text=k+j
			}
		}
	}
	setTimeout( function() {
		h.scrollTop=b
	},0);
	h.focus();
	return true
};
Typecho.highlight= function(a) {
	if(a) {
		var b=$(a);
		if(b) {
			b.set("tween", {
				duration:1500
			});
			var c=b.getStyle("background-color");
			if(!c||"transparent"==c) {
				c="#F7FBE9"
			}
			b.tween("background-color","#AACB36",c)
		}
	}
};
Typecho.autoDisableSubmit= function() {
	$(document).getElements("input[type=submit]").removeProperty("disabled");
	$(document).getElements("button[type=submit]").removeProperty("disabled");
	var a= function(b) {
		b.stopPropagation();
		$(this).setProperty("disabled",true);
		$(this).getParent("form").submit();
		return false
	};
	$(document).getElements("input[type=submit]").addEvent("click",a);
	$(document).getElements("button[type=submit]").addEvent("click",a)
};
Element.implement({
	getSelectedRange: function() {
		if(!Browser.Engine.trident) {
			return {
				start:this.selectionStart,
				end:this.selectionEnd
			}
		}
		var e= {
			start:0,
			end:0
		};
		var a=this.getDocument().selection.createRange();
		if(!a||a.parentElement()!=this) {
			return e
		}
		var c=a.duplicate();
		if(this.type=="text") {
			e.start=0-c.moveStart("character",-100000);
			e.end=e.start+a.text.length
		} else {
			var b=this.value;
			var d=b.length-b.match(/[\n\r]*$/)[0].length;
			c.moveToElementText(this);
			c.setEndPoint("StartToEnd",a);
			e.end=d-c.text.length;
			c.setEndPoint("StartToStart",a);
			e.start=d-c.text.length
		}
		return e
	},
	selectRange: function(d,a) {
		if(Browser.Engine.trident) {
			var c=this.value.substr(d,a-d).replace(/\r/g,"").length;
			d=this.value.substr(0,d).replace(/\r/g,"").length;
			var b=this.createTextRange();
			b.collapse(true);
			b.moveEnd("character",d+c);
			b.moveStart("character",d);
			b.select()
		} else {
			this.focus();
			this.setSelectionRange(d,a)
		}
		return this
	}
});
Typecho.autoComplete= function(k,i) {
	var o=",",n,c=-1,g=false,p=$(document).getElement(k).setProperty("autocomplete","off");
	var a= function() {
		var r=0,s=p.get("value");
		n=[];
		if(s.length>0) {
			s.split(o).each( function(w,u) {
				var t=r+w.length,v=0,x=0;
				w=w.replace(/(\s*)(.*)(\s*)/, function(A,z,y,B) {
					v=z.length;
					x=B.length;
					return y
				});
				n[u]= {
					txt:w,
					start:u*1+r,
					end:u*1+t,
					offsetStart:u*1+r+v,
					offsetEnd:u*1+t-x
				};
				r=t
			})
		}
	};
	var j= function(r,t) {
		return t?t.txt.substr(0,r-t.offsetStart):""
	};
	var m= function(r) {
		var t=r.length>0?i.filter( function(u) {
			return 0==u.indexOf(r)
		}):[];
		var s=r.length>0?i.filter( function(u) {
			return(0==u.toLowerCase().indexOf(r.toLowerCase())&&!t.contains(u))
		}):[];
		return t.extend(s)
	};
	var f= function(r,t) {
		p.selectRange(t.offsetStart>r?t.offsetStart:r,t.offsetEnd)
	};
	var q= function(t) {
		for(var r in n) {
			if(t>=n[r].start&&t<=n[r].end) {
				return n[r]
			}
		}
		return false
	};
	var b= function(r,t,u) {
		var v=p.get("value");
		return p.set("value",v.substr(0,t)+r+v.substr(u))
	};
	var l= function(r,s) {
		c=-1;
		g=false;
		var t=new Element("ul", {
			"class":"autocompleter-choices",
			styles: {
				width:p.getSize().x-2,
				left:p.getPosition().x,
				top:p.getPosition().y+p.getSize().y
			}
		});
		s.each( function(v,u) {
			t.grab(new Element("li", {
				rel:u,
				html:'<span class="autocompleter-queried">'+v.substr(0,r.length)+"</span>"+v.substr(r.length),
				events: {
					mouseover: function() {
						g=true;
						this.addClass("autocompleter-hover")
					},
					mouseleave: function() {
						g=false;
						this.removeClass("autocompleter-hover")
					},
					click: function() {
						var y=parseInt(this.get("rel"));
						var x=p.getSelectedRange().start,w=q(x);
						b(s[y],w.offsetStart,w.offsetEnd);
						a();
						w=q(x);
						p.selectRange(w.offsetEnd,w.offsetEnd);
						e()
					}
				}
			}))
		});
		$(document).getElement("body").grab(t)
	};
	var e= function() {
		var r=$(document).getElement(".autocompleter-choices");
		if(r) {
			r.destroy();
			g=false
		}
	};
	a();
	var h,d;
	p.addEvents({
		mouseup: function(t) {
			var s=p.getSelectedRange().start,r=q(s);
			e();
			f(s,r);
			this.fireEvent("keyup",t);
			t.stop();
			return false
		},
		blur: function() {
			if(!g) {
				e()
			}
		},
		keydown: function(t) {
			a();
			var s=p.getSelectedRange().start,r=q(s);
			switch(t.key) {
				case"up":
					if(d.length>0&&c>=0) {
						if(c<d.length) {
							$(document).getElement(".autocompleter-choices li[rel="+c+"]").removeClass("autocompleter-selected")
						}
						if(c>0) {
							c--
						} else {
							c=d.length-1
						}
						$(document).getElement(".autocompleter-choices li[rel="+c+"]").addClass("autocompleter-selected");
						b(d[c],r.offsetStart,r.offsetEnd);
						a();
						r=q(s);
						f(s,r)
					}
					t.stop();
					return false;
				case"down":
					if(d.length>0&&c<d.length) {
						if(c>=0) {
							$(document).getElement(".autocompleter-choices li[rel="+c+"]").removeClass("autocompleter-selected")
						}
						if(c<d.length-1) {
							c++
						} else {
							c=0
						}
						$(document).getElement(".autocompleter-choices li[rel="+c+"]").addClass("autocompleter-selected");
						b(d[c],r.offsetStart,r.offsetEnd);
						a();
						r=q(s);
						f(s,r)
					}
					t.stop();
					return false;
				case"enter":
					e();
					p.selectRange(r.offsetEnd,r.offsetEnd);
					t.stop();
					return false;
				default:
					break
			}
		},
		keyup: function(t) {
			a();
			var s=p.getSelectedRange().start,r=q(s);
			switch(t.key) {
				case"left":
				case"right":
				case"backspace":
				case"delete":
				case"esc":
					e();
					t.key="a";
					this.fireEvent("keyup",t,1000);
					break;
				case"enter":
					return false;
				case"up":
				case"down":
					return false;
				case"space":
				default:
					e();
					h=j(s,r);
					d=m(h);
					if(d.length>0) {
						f(s,r);
						l(h,d)
					}
					break
			}
		}
	})
};