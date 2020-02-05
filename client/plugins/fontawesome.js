import Vue from 'vue'
import { library } from '@fortawesome/fontawesome-svg-core'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

import {
  faPlay, faChevronLeft, faBan, faUser, faPlus, faTrashAlt, faEdit, faCheck, faTimes,
  faPlusSquare, faQuestionCircle, faLongArrowAltLeft, faCog, faSignOutAlt, faTasks,
  faTrophy, faWrench, faFire, faChevronDown, faInfo, faBell, faHeart, faBolt, faLock,
  faCircleNotch, faFolderOpen, faEye, faComment, faCaretRight, faLightbulb, faComments, 
  faBug, faSortAmountUp, faInfoCircle
} from '@fortawesome/free-solid-svg-icons'

// Brands
import { faTwitter } from '@fortawesome/free-brands-svg-icons'

library.add(
  faPlay, faChevronLeft, faBan, faUser, faPlus, faTrashAlt, faEdit, faCheck, faTimes,
  faPlusSquare, faQuestionCircle, faLongArrowAltLeft, faCog, faSignOutAlt, faTasks,
  faTrophy, faWrench, faFire, faChevronDown, faInfo, faBell, faHeart, faBolt, faLock,
  faCircleNotch, faFolderOpen, faEye, faComment, faTwitter, faCaretRight, faLightbulb, 
  faComments, faBug, faSortAmountUp, faInfoCircle
)

Vue.component('fa', FontAwesomeIcon)
