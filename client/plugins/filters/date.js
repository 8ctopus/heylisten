import Vue from 'vue'
import moment from 'moment'

Vue.filter('timeHuman', value => {
  const time = moment.utc(value)
  const timeZone = moment().utcOffset()
  // Less than 1 hour
  // Display N minutes ago
  if (time.isAfter(moment().subtract(1, 'hours'))) {
    return time.utcOffset(timeZone).fromNow()
  }
  else if (time.isAfter(moment().subtract(2, 'days'))) {
    return time.utcOffset(timeZone).calendar()
  }
  else {
    return time.utcOffset(timeZone).format('LL')
  }
})
