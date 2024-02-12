import { LocationShort } from "@/models/locations/location/short";

export interface StateShort {
  id: number;
  name: string;
  locations: LocationShort[];
}

const stateShortDataType = "short";
export { stateShortDataType };
