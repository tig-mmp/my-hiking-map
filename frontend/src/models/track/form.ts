export interface TrackForm {
  name?: string;
  url?: string;
  startDistrictId?: number;
  startCountyId?: number;
  startLocationId?: number;
  description?: string;
  distance?: number;
  routeCode?: string;
  difficulty?: number;
  landscape?: number;
  enjoyment?: number;
  trackUrl?: string;
  officialUrl?: string;
  groupName?: string;
  guide?: string;
  weekNumber?: number;
  isMoita?: boolean;
  duration?: string;
  date?: Date;
  startedAt?: string;
  endedAt?: string;
}

const trackFormDataType = "form";
export { trackFormDataType };
