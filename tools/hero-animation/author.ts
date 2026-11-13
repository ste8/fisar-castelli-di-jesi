import {writeFileSync} from 'node:fs';
import {ANIMATION_DOCUMENT_VERSION,assertValidAnimationDocument,constant,withComputedRevision,type AnimationDocument,type Transform25D} from '@clayzo/animation/authoring';
// Direzione: un solo calice, movimento breve del vino, poi quiete.
// Geometria e palette sono quelle dell'illustrazione approvata; il marchio resta fermo.
const WIDTH=640,HEIGHT=960,TPS=240,DURATION=1152;
const transform=(x=0,y=0):Transform25D=>({position:constant({x,y,z:0}),anchor:constant({x:0,y:0,z:0}),scale:constant({x:1,y:1,z:1}),rotation:constant({x:0,y:0,z:0}),skew:constant({x:0,y:0})});
const base=(id:string,x=0,y=0)=>({id,name:id,visible:true,inTick:0,outTick:DURATION,opacity:constant(1),transform:transform(x,y),blendMode:'normal' as const});
const shader=`
uniform shader inputImage;
uniform float elapsed;
uniform float2 unitScale;
half4 main(float2 p) {
  float2 uv=p/unitScale;
  float t=clamp(elapsed/4.8,0.0,1.0);
  float envelope=sin(t*3.14159265)*exp(-t*1.45);
  float nx=(uv.x-320.0)/180.0;
  float sides=1.0-smoothstep(0.72,0.98,abs(nx));
  float liquid=smoothstep(370.0,389.0,uv.y)*(1.0-smoothstep(460.0,530.0,uv.y))*sides;
  float rocking=sin(elapsed*3.3)*nx*10.0;
  float ripple=sin(nx*4.2-elapsed*3.3)*2.0;
  float dy=(rocking+ripple)*envelope*liquid;
  half4 moved=unpremul(inputImage.eval(p+float2(0.0,dy)*unitScale));
  float top=smoothstep(36.0,72.0,uv.y)*(1.0-smoothstep(145.0,165.0,uv.y));
  float left=exp(-pow((uv.x-(218.0-uv.y*0.20))/12.0,2.0));
  float right=exp(-pow((uv.x-(424.0+uv.y*0.18))/9.0,2.0));
  float light=(left+right*0.6)*top*envelope*0.15;
  moved.rgb=mix(moved.rgb,half3(1.0),light);
  return half4(moved.rgb*moved.a,moved.a);
}`;
const document=withComputedRevision({version:ANIMATION_DOCUMENT_VERSION,id:'fisar-calice-prova',revision:'',name:'Calice FISAR — vino e riflesso',canvas:{width:WIDTH,height:HEIGHT,pixelAspectRatio:1,backgroundColor:{r:0,g:0,b:0,a:0}},timing:{ticksPerSecond:TPS,frameRate:{numerator:30,denominator:1},durationTicks:DURATION,displayStartTick:0},assets:{glass:{id:'glass',type:'image',uri:'./calice.webp',width:WIDTH,height:HEIGHT,mimeType:'image/webp'}},compositions:{main:{id:'main',name:'Calice',width:WIDTH,height:HEIGHT,durationTicks:DURATION,nodes:{glass:{...base('glass',WIDTH/2,HEIGHT/2),type:'image',assetId:'glass',size:constant({width:WIDTH,height:HEIGHT}),fit:'contain'},wine:{...base('wine'),type:'effect',inputId:'glass',effect:'custom-sksl',implementation:shader,parameters:[{id:'elapsed',name:'elapsed',valueType:'number',minimum:0,maximum:4.8,value:{kind:'keyframed',keyframes:[{id:'time-start',tick:0,value:0,interpolation:'linear'},{id:'time-end',tick:DURATION,value:4.8,interpolation:'hold'}]}},{id:'unitScale',name:'unitScale',valueType:'point',minimum:{x:0,y:0},maximum:{x:2,y:2},value:constant({x:1,y:1})}],capability:{requires:['runtime-effect','offscreen-surface'],fallback:'error',maxTemporarySurfaces:2}}},rootNodeIds:['wine']}},rootCompositionId:'main',components:{},markers:[],capabilities:{required:[],optional:[],unsupportedPolicy:'error'}} satisfies AnimationDocument);
assertValidAnimationDocument(document);
writeFileSync(new URL('../../theme/fisar-cdj/assets/animations/hero-wine.json',import.meta.url),JSON.stringify(document,null,2));
console.log('Documento del vino validato, revisione '+document.revision);
