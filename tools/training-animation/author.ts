import { writeFileSync } from 'node:fs';
import { ANIMATION_DOCUMENT_VERSION, assertValidAnimationDocument, base, constant, fill, hex, keyed, pen, circleContour, serializeSvgPath, stroke, trim, withComputedRevision, type AnimationDocument, type PathContour, type SceneNode } from '@clayzo/animation/authoring';

// Three original monoline illustrations; the SVG fallback uses exactly the same contours.
const WIDTH = 900, HEIGHT = 180, END = 1080;
const INK = '#641629', GOLD = '#b9aa54', PAPER = '#f7f3ef';
const nodes: Record<string, SceneNode> = {};
const svg: string[] = [];
const ease = { x1: .25, y1: .1, x2: .25, y2: 1 };
function path(id: string, contours: PathContour[], x: number, y: number, color: string, start: number, stop: number, width = 2.8) {
  const data = { contours };
  nodes[id] = { ...base(id, { outTick: END, x, y }), type: 'path', path: constant(data),
    style: { stroke: stroke(hex(color), width) },
    operators: [trim(id + '.trim', { end: keyed(id + '.end', [[start, 0, ease], [stop, 1]]) })] };
  svg.push(`<path d="${serializeSvgPath(data)}" transform="translate(${x} ${y})" fill="none" stroke="${color}" stroke-width="${width}" stroke-linecap="round" stroke-linejoin="round"/>`);
}
function backdrop(id: string, x: number) {
  nodes[id] = { ...base(id, { outTick: END, x, y: 86 }), type: 'ellipse', size: constant({ width: 116, height: 116 }), style: { fill: fill(hex(PAPER)) } };
  svg.push(`<circle cx="${x}" cy="86" r="58" fill="${PAPER}"/>`);
}
const line = (x: number, y: number) => pen(x, y, { polyline: true });
for (const [id, x] of [['one',150],['two',450],['three',750]] as const) backdrop(id, x);
// A quiet guide remains visible from the first frame.
for (const [id, x] of [['route-one',208],['route-two',508]] as const) {
  nodes[id + '.guide'] = { ...base(id + '.guide', { outTick: END, x, y: 86 }), type:'path',
    path: constant({contours:[line(0,0).lineTo(184,0).open()]}), style:{stroke:stroke(hex('#e6e0dc'),1.4,{dash:[3,7]})} };
  svg.push(`<path d="M ${x} 86 h184" stroke="#e6e0dc" stroke-width="1.4" stroke-dasharray="3 7"/>`);
}
const glass = [line(-22,-33).lineTo(22,-33).lineTo(26,-9).curveTo({x:30,y:23},{x:-30,y:23},-26,-9).lineTo(-22,-33).open(), line(0,15).lineTo(0,40).open(),line(-18,40).lineTo(18,40).open()];
path('glass',glass,150,82,INK,36,228);
path('wine',[line(-25,-4).curveTo({x:-8,y:-10},{x:8,y:3},25,-4).open()],150,82,GOLD,144,252);
path('first-link',[line(0,0).lineTo(180,0).lineTo(173,-5).lineTo(180,0).lineTo(173,5).open()],210,86,GOLD,252,360,2);
const globe = [circleContour({x:0,y:0},36,{polyline:true}),line(0,-36).curveTo({x:-28,y:-18},{x:-28,y:18},0,36).curveTo({x:28,y:18},{x:28,y:-18},0,-36).open(),line(-36,0).lineTo(36,0).open(),line(-31,-18).curveTo({x:-12,y:-11},{x:12,y:-11},31,-18).open(),line(-31,18).curveTo({x:-12,y:11},{x:12,y:11},31,18).open()];
path('territories',globe,450,86,INK,360,552,2.5);
path('territory-pin',[circleContour({x:17,y:-14},5,{polyline:true})],450,86,GOLD,492,576,3);
path('second-link',[line(0,0).lineTo(180,0).lineTo(173,-5).lineTo(180,0).lineTo(173,5).open()],510,86,GOLD,576,684,2);
const plate = [circleContour({x:-6,y:0},29,{polyline:true}),circleContour({x:-6,y:0},20,{polyline:true}),line(-47,-28).lineTo(-47,-9).curveTo({x:-47,y:0},{x:-35,y:0},-35,-9).lineTo(-35,-28).open(),line(-41,-28).lineTo(-41,34).open()];
path('plate',plate,750,86,INK,684,876,2.5);
path('pairing-wine',[line(37,-27).lineTo(55,-27).lineTo(58,-10).curveTo({x:59,y:7},{x:33,y:7},34,-10).lineTo(37,-27).open(),line(46,3).lineTo(46,28).open(),line(35,28).lineTo(57,28).open(),line(35,-8).lineTo(57,-8).open()],750,86,GOLD,756,900,2.5);
for (const [id,x,start] of [['one',150,228],['two',450,552],['three',750,900]] as const) {
  path('done-'+id,[line(-5,0).lineTo(-1,4).lineTo(7,-5).open()],x,161,GOLD,start,start+72,2.5);
}
const document = withComputedRevision({ version: ANIMATION_DOCUMENT_VERSION, id:'fisar-training-journey',revision:'',name:'Il percorso sommelier — tre livelli',
  canvas:{width:WIDTH,height:HEIGHT,pixelAspectRatio:1,backgroundColor:{r:0,g:0,b:0,a:0}},
  timing:{ticksPerSecond:240,frameRate:{numerator:30,denominator:1},durationTicks:END,displayStartTick:0},assets:{},
  compositions:{main:{id:'main',name:'Tre livelli',width:WIDTH,height:HEIGHT,durationTicks:END,nodes,rootNodeIds:Object.keys(nodes)}},
  rootCompositionId:'main',components:{},markers:[],capabilities:{required:[],optional:[],unsupportedPolicy:'error'} } satisfies AnimationDocument);
assertValidAnimationDocument(document);
writeFileSync(new URL('../../theme/fisar-cdj/assets/animations/training-journey.json',import.meta.url),JSON.stringify(document,null,2));
writeFileSync(new URL('../../theme/fisar-cdj/assets/images/training-journey.svg',import.meta.url),`<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 ${WIDTH} ${HEIGHT}">${svg.join('')}</svg>\n`);
console.log('Percorso validato: '+document.revision);
